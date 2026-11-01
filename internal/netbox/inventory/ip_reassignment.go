package inventory

import (
	"context"
	"fmt"
	"net/netip"

	"github.com/bl4ko/netbox-ssot/internal/constants"
	"github.com/bl4ko/netbox-ssot/internal/netbox/objects"
	"github.com/bl4ko/netbox-ssot/internal/netbox/service"
	"github.com/bl4ko/netbox-ssot/internal/utils"
)

func ipHost(address string) string {
	if prefix, err := netip.ParsePrefix(address); err == nil {
		return prefix.Addr().String()
	}
	return address
}

func ipVRFID(ip *objects.IPAddress) int {
	if ip.VRF != nil {
		return ip.VRF.ID
	}
	return 0
}

func chooseReusableIPAddress(ips []objects.IPAddress, desired *objects.IPAddress) (*objects.IPAddress, error) {
	var found *objects.IPAddress
	for i := range ips {
		ip := &ips[i]
		if ipHost(ip.Address) != ipHost(desired.Address) || ipVRFID(ip) != ipVRFID(desired) {
			continue
		}
		if found != nil && found.ID != ip.ID {
			return nil, fmt.Errorf("multiple existing records for IP %s in VRF %d; refusing ambiguous reassignment",
				desired.Address, ipVRFID(desired))
		}
		found = ip
	}
	return found, nil
}

// The caller holds ipAddressesLock for lookup, API writes and reindexing.
func (nbi *NetboxInventory) findReusableIPAddress(desired *objects.IPAddress) (*objects.IPAddress, error) {
	var candidates []objects.IPAddress
	for _, interfaces := range nbi.ipAddressesIndex {
		for _, parents := range interfaces {
			for _, addresses := range parents {
				for _, ip := range addresses {
					if ipHost(ip.Address) == ipHost(desired.Address) && ipVRFID(ip) == ipVRFID(desired) {
						candidates = append(candidates, *ip)
					}
				}
			}
		}
	}
	return chooseReusableIPAddress(candidates, desired)
}

func (nbi *NetboxInventory) reassignIPAddress(
	ctx context.Context, existing, desired *objects.IPAddress,
) (*objects.IPAddress, error) {
	moving := existing.AssignedObjectID != desired.AssignedObjectID ||
		existing.AssignedObjectType != desired.AssignedObjectType
	if moving && existing.AssignedObjectID != 0 && !existing.HasTag(nbi.SsotTag) {
		return nil, fmt.Errorf("IP %s (ID %d) is assigned outside SSOT management; refusing reassignment",
			existing.Address, existing.ID)
	}
	diff, err := utils.JSONDiffMapExceptID(desired, existing, false, nbi.SourcePriority)
	if err != nil {
		return nil, err
	}
	if (existing.AssignedObjectID != desired.AssignedObjectID && diff["assigned_object_id"] == nil) ||
		(existing.AssignedObjectType != desired.AssignedObjectType && diff["assigned_object_type"] == nil) {
		return nil, fmt.Errorf("source priority prevents reassignment of IP %s (ID %d)", existing.Address, existing.ID)
	}
	if moving {
		if err := nbi.clearPreviousPrimaryIP(ctx, existing); err != nil {
			return nil, fmt.Errorf("clear previous primary reference for IP %s: %w", existing.Address, err)
		}
	}
	// Explicitly clear stale DNS when an address moves to a VM without a hostname.
	diff["dns_name"] = desired.DNSName
	diff["assigned_object_type"] = desired.AssignedObjectType
	diff["assigned_object_id"] = desired.AssignedObjectID
	patched, err := service.Patch[objects.IPAddress](ctx, nbi.NetboxAPI, existing.ID, diff)
	if err != nil {
		return nil, fmt.Errorf("reassign IP %s (ID %d): %w", existing.Address, existing.ID, err)
	}
	if nbi.NetboxAPI.DryRun {
		patched = desired
		patched.ID = existing.ID
	}
	objType, ifaceName, parentName, err := nbi.getIndexValuesForIPAddress(desired)
	if err != nil {
		return nil, err
	}
	// Remove every old assignment key, including entries with an old mask.
	for _, interfaces := range nbi.ipAddressesIndex {
		for _, parents := range interfaces {
			for _, addresses := range parents {
				for key, ip := range addresses {
					if ip.ID == existing.ID {
						delete(addresses, key)
					}
				}
			}
		}
	}
	// AddIPAddress has already initialized the destination buckets.
	nbi.ipAddressesIndex[objType][ifaceName][parentName][ipAddressIndexKey(patched)] = patched
	nbi.OrphanManager.RemoveItem(existing)
	nbi.Logger.Infof(ctx, "Reassigned existing IP %s (ID %d) to %s %d",
		patched.Address, patched.ID, patched.AssignedObjectType, patched.AssignedObjectID)
	return patched, nil
}

func (nbi *NetboxInventory) clearPreviousPrimaryIP(ctx context.Context, ip *objects.IPAddress) error {
	if ip.AssignedObjectID == 0 {
		return nil
	}
	switch ip.AssignedObjectType {
	case constants.ContentTypeVirtualizationVMInterface:
		iface := nbi.GetVMInterfaceByID(ip.AssignedObjectID)
		if iface == nil {
			items, err := service.GetAll[objects.VMInterface](ctx, nbi.NetboxAPI,
				fmt.Sprintf("&id=%d", ip.AssignedObjectID))
			if err != nil {
				return err
			}
			if len(items) != 1 {
				return fmt.Errorf("cannot resolve previous VM interface %d", ip.AssignedObjectID)
			}
			iface = &items[0]
		}
		if iface.VM == nil {
			return fmt.Errorf("VM interface %d has no parent", iface.ID)
		}
		nbi.vmsLock.Lock()
		defer nbi.vmsLock.Unlock()
		owners, err := service.GetAll[objects.VM](ctx, nbi.NetboxAPI, fmt.Sprintf("&id=%d", iface.VM.ID))
		if err != nil {
			return err
		}
		if len(owners) != 1 {
			return fmt.Errorf("cannot resolve previous VM %d", iface.VM.ID)
		}
		if err := clearPrimaryIP[objects.VM](ctx, nbi.NetboxAPI, &owners[0], ip.ID); err != nil {
			return err
		}
		clearCachedPrimaryIP(nbi.vmsIndexByID[iface.VM.ID], ip.ID)
		for _, clusters := range nbi.vmsIndexByNameAndClusterID {
			for _, vm := range clusters {
				if vm.ID == iface.VM.ID {
					clearCachedPrimaryIP(vm, ip.ID)
				}
			}
		}
	case constants.ContentTypeDcimInterface:
		iface := nbi.GetInterfaceByID(ip.AssignedObjectID)
		if iface == nil {
			items, err := service.GetAll[objects.Interface](ctx, nbi.NetboxAPI,
				fmt.Sprintf("&id=%d", ip.AssignedObjectID))
			if err != nil {
				return err
			}
			if len(items) != 1 {
				return fmt.Errorf("cannot resolve previous device interface %d", ip.AssignedObjectID)
			}
			iface = &items[0]
		}
		if iface.Device == nil {
			return fmt.Errorf("device interface %d has no parent", iface.ID)
		}
		nbi.devicesLock.Lock()
		defer nbi.devicesLock.Unlock()
		owners, err := service.GetAll[objects.Device](ctx, nbi.NetboxAPI, fmt.Sprintf("&id=%d", iface.Device.ID))
		if err != nil {
			return err
		}
		if len(owners) != 1 {
			return fmt.Errorf("cannot resolve previous device %d", iface.Device.ID)
		}
		if err := clearPrimaryIP[objects.Device](ctx, nbi.NetboxAPI, &owners[0], ip.ID); err != nil {
			return err
		}
		clearCachedDevicePrimaryIP(nbi.devicesIndexByID[iface.Device.ID], ip.ID)
		for _, sites := range nbi.devicesIndexByNameAndSiteID {
			for _, device := range sites {
				if device.ID == iface.Device.ID {
					clearCachedDevicePrimaryIP(device, ip.ID)
				}
			}
		}
	default:
		return fmt.Errorf("unsupported previous assignment type %s", ip.AssignedObjectType)
	}
	return nil
}

func clearPrimaryIP[T any](
	ctx context.Context, client *service.NetboxClient, owner objects.IPAddressOwner, id int,
) error {
	diff := make(map[string]interface{})
	if ip := owner.GetPrimaryIPv4Address(); ip != nil && ip.ID == id {
		diff["primary_ip4"] = nil
	}
	if ip := owner.GetPrimaryIPv6Address(); ip != nil && ip.ID == id {
		diff["primary_ip6"] = nil
	}
	if len(diff) == 0 {
		return nil
	}
	_, err := service.Patch[T](ctx, client, owner.GetID(), diff)
	return err
}

func clearCachedPrimaryIP(vm *objects.VM, id int) {
	if vm == nil {
		return
	}
	if vm.PrimaryIPv4 != nil && vm.PrimaryIPv4.ID == id {
		vm.PrimaryIPv4 = nil
	}
	if vm.PrimaryIPv6 != nil && vm.PrimaryIPv6.ID == id {
		vm.PrimaryIPv6 = nil
	}
}

func clearCachedDevicePrimaryIP(device *objects.Device, id int) {
	if device == nil {
		return
	}
	if device.PrimaryIPv4 != nil && device.PrimaryIPv4.ID == id {
		device.PrimaryIPv4 = nil
	}
	if device.PrimaryIPv6 != nil && device.PrimaryIPv6.ID == id {
		device.PrimaryIPv6 = nil
	}
}
