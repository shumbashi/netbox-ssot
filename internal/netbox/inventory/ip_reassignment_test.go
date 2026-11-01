package inventory

import (
	"context"
	"encoding/json"
	"io"
	"log"
	"net/http"
	"reflect"
	"strings"
	"testing"

	"github.com/bl4ko/netbox-ssot/internal/constants"
	"github.com/bl4ko/netbox-ssot/internal/logger"
	"github.com/bl4ko/netbox-ssot/internal/netbox/objects"
	"github.com/bl4ko/netbox-ssot/internal/netbox/service"
)

type ipTestTransport func(*http.Request) (*http.Response, error)

func (transport ipTestTransport) RoundTrip(req *http.Request) (*http.Response, error) {
	return transport(req)
}

func TestAddIPAddressReusesReleasedAddress(t *testing.T) { //nolint:gocyclo
	tests := []struct {
		name             string
		cached           bool
		address          string
		oldMask          string
		vrf              *objects.VRF
		failPatch        bool
		dryRun           bool
		missingInterface bool
		primaryFailure   bool
	}{
		{name: "cached IPv4", cached: true, address: "192.0.2.1/32", oldMask: "192.0.2.1/32"},
		{name: "uncached IPv4", address: "192.0.2.1/32", oldMask: "192.0.2.1/32"},
		{name: "changed mask", cached: true, address: "192.0.2.1/32", oldMask: "192.0.2.1/24"},
		{name: "IPv6", cached: true, address: "2001:db8::1/128", oldMask: "2001:db8::1/64"},
		{name: "VRF", cached: true, address: "192.0.2.1/32", oldMask: "192.0.2.1/32",
			vrf: &objects.VRF{NetboxObject: objects.NetboxObject{ID: 7}}},
		{name: "missing cached interface", address: "192.0.2.1/32", oldMask: "192.0.2.1/32", missingInterface: true},
		{name: "primary clear failure", cached: true, address: "192.0.2.1/32", oldMask: "192.0.2.1/32", primaryFailure: true},
		{name: "patch failure", address: "192.0.2.1/32", oldMask: "192.0.2.1/32", failPatch: true},
		{name: "dry run", cached: true, address: "192.0.2.1/32", oldMask: "192.0.2.1/32", dryRun: true},
	}
	for _, tt := range tests {
		t.Run(tt.name, func(t *testing.T) {
			ctx := context.WithValue(context.Background(), constants.CtxSourceKey, "cloud")
			ssotTag := &objects.Tag{ID: 1, Name: constants.SsotTagName}
			oldIP := &objects.IPAddress{
				NetboxObject: objects.NetboxObject{ID: 42, Tags: []*objects.Tag{ssotTag},
					CustomFields: map[string]interface{}{"source": "cloud", "keep_me": "manual"}},
				Address: tt.oldMask, VRF: tt.vrf, DNSName: "old.example.com",
				AssignedObjectType: constants.ContentTypeVirtualizationVMInterface, AssignedObjectID: 10,
			}
			oldVM := &objects.VM{NetboxObject: objects.NetboxObject{ID: 100}, Name: "old-vm"}
			if strings.Contains(tt.address, ":") {
				oldVM.PrimaryIPv6 = oldIP
			} else {
				oldVM.PrimaryIPv4 = oldIP
			}
			newVM := &objects.VM{NetboxObject: objects.NetboxObject{ID: 200}, Name: "new-vm"}
			log := &logger.Logger{Logger: log.New(io.Discard, "", 0)}
			nbi := &NetboxInventory{
				Logger: log, Ctx: ctx, SsotTag: ssotTag, OrphanManager: NewOrphanManager(log),
				ipAddressesIndex: make(map[constants.ContentType]map[string]map[string]map[string]*objects.IPAddress),
				vmInterfacesIndexByID: map[int]*objects.VMInterface{
					10: {NetboxObject: objects.NetboxObject{ID: 10}, Name: "eth0", VM: oldVM},
					20: {NetboxObject: objects.NetboxObject{ID: 20}, Name: "eth0", VM: newVM},
				},
				vmsIndexByID:               map[int]*objects.VM{100: oldVM, 200: newVM},
				vmsIndexByNameAndClusterID: map[string]map[int]*objects.VM{"old-vm": {1: oldVM}},
			}
			nbi.OrphanManager.AddItem(oldIP)
			if tt.cached {
				nbi.verifyIPAddressIndexExists(constants.ContentTypeVirtualizationVirtualMachine, "eth0", "old-vm")
				oldAddresses := nbi.ipAddressesIndex[constants.ContentTypeVirtualizationVirtualMachine]["eth0"]["old-vm"]
				oldAddresses[ipAddressIndexKey(oldIP)] = oldIP
			}
			if tt.missingInterface {
				delete(nbi.vmInterfacesIndexByID, 10)
			}
			var requests []string
			primaryCleared := false
			transport := ipTestTransport(func(req *http.Request) (*http.Response, error) {
				requests = append(requests, req.Method+" "+req.URL.Path)
				var result interface{}
				status := http.StatusOK
				switch req.Method + " " + req.URL.Path {
				case "POST /api/ipam/ip-addresses/":
					status = http.StatusBadRequest
					result = map[string]interface{}{"address": []string{"Duplicate IP address found in global table"}}
				case "GET /api/ipam/ip-addresses/":
					if req.URL.Query().Get("address") != ipHost(tt.address) {
						t.Error("duplicate lookup must match the host address regardless of mask")
					}
					result = service.Response[objects.IPAddress]{Results: []objects.IPAddress{*oldIP}}
				case "GET /api/virtualization/interfaces/":
					result = service.Response[objects.VMInterface]{Results: []objects.VMInterface{
						{NetboxObject: objects.NetboxObject{ID: 10}, VM: oldVM},
					}}
				case "GET /api/virtualization/virtual-machines/":
					result = service.Response[objects.VM]{Results: []objects.VM{*oldVM}}
				case "PATCH /api/virtualization/virtual-machines/100/":
					var patch map[string]interface{}
					if err := json.NewDecoder(req.Body).Decode(&patch); err != nil {
						t.Fatal(err)
					}
					field := "primary_ip4"
					if strings.Contains(tt.address, ":") {
						field = "primary_ip6"
					}
					if value, ok := patch[field]; !ok || value != nil {
						t.Errorf("expected explicit null for old primary IP, got %v", patch)
					}
					primaryCleared = true
					result = oldVM
					if tt.primaryFailure {
						status = http.StatusForbidden
						result = map[string]interface{}{"error": "primary update denied"}
					}
				case "PATCH /api/ipam/ip-addresses/42/":
					if !primaryCleared {
						t.Error("IP reassigned before clearing old primary")
					}
					var patch map[string]interface{}
					if err := json.NewDecoder(req.Body).Decode(&patch); err != nil {
						t.Fatal(err)
					}
					if patch["assigned_object_id"] != float64(20) || patch["dns_name"] != "" {
						t.Errorf("incorrect reassignment fields: %v", patch)
					}
					if fields, ok := patch["custom_fields"].(map[string]interface{}); ok && fields["keep_me"] != "manual" {
						t.Error("manual custom fields were lost")
					}
					updated := *oldIP
					updated.Address, updated.AssignedObjectID, updated.DNSName = tt.address, 20, ""
					result = &updated
					if tt.failPatch {
						status = http.StatusBadRequest
						result = map[string]interface{}{"error": "reassignment blocked"}
					}
				default:
					t.Fatalf("unexpected request: %s %s", req.Method, req.URL)
				}
				body, err := json.Marshal(result)
				if err != nil {
					t.Fatal(err)
				}
				return &http.Response{StatusCode: status, Body: io.NopCloser(strings.NewReader(string(body))),
					Header: make(http.Header), Request: req}, nil
			})
			nbi.NetboxAPI = &service.NetboxClient{Logger: log, BaseURL: "https://netbox.invalid", Timeout: 1,
				HTTPClient: &http.Client{Transport: transport}, DryRun: tt.dryRun}
			got, err := nbi.AddIPAddress(ctx, &objects.IPAddress{
				Address: tt.address, VRF: tt.vrf, AssignedObjectType: constants.ContentTypeVirtualizationVMInterface,
				AssignedObjectID: 20, Status: &objects.IPAddressStatusActive,
			})
			if tt.primaryFailure {
				if err == nil || !strings.Contains(err.Error(), "primary update denied") {
					t.Fatalf("expected primary clear error, got %v", err)
				}
				for _, request := range requests {
					if request == "PATCH /api/ipam/ip-addresses/42/" {
						t.Error("address moved after failure to clear primary")
					}
				}
				return
			}
			if tt.failPatch {
				if err == nil || !strings.Contains(err.Error(), "reassignment blocked") {
					t.Fatalf("expected actual PATCH error instead of duplicate error, got %v", err)
				}
				if _, ok := nbi.OrphanManager.Items[constants.IPAddressesAPIPath][42]; !ok {
					t.Error("failed reassignment removed orphan tracking")
				}
				return
			}
			if err != nil {
				t.Fatal(err)
			}
			if got.ID != 42 || got.AssignedObjectID != 20 {
				t.Fatalf("expected existing ID 42 assigned to new interface 20, got %+v", got)
			}
			addresses := nbi.ipAddressesIndex[constants.ContentTypeVirtualizationVirtualMachine]["eth0"]
			if len(addresses["old-vm"]) != 0 || addresses["new-vm"][ipAddressIndexKey(got)] != got {
				t.Error("assignment indexes were not moved")
			}
			if _, ok := nbi.OrphanManager.Items[constants.IPAddressesAPIPath][42]; ok {
				t.Error("reassigned address is still marked orphaned")
			}
			if oldVM.PrimaryIPv4 != nil || oldVM.PrimaryIPv6 != nil {
				t.Error("old VM cache still has a primary IP reference")
			}
			if tt.dryRun && !reflect.DeepEqual(requests, []string{"GET /api/virtualization/virtual-machines/"}) {
				t.Errorf("dry run sent unexpected requests: %v", requests)
			}
			if tt.cached {
				for _, request := range requests {
					if strings.HasPrefix(request, "POST ") {
						t.Error("cached address must be reused without creating a duplicate")
					}
				}
			}
		})
	}
}

func TestChooseReusableIPAddressScope(t *testing.T) {
	global := objects.IPAddress{NetboxObject: objects.NetboxObject{ID: 1}, Address: "192.0.2.1/24"}
	vrf := global
	vrf.ID, vrf.VRF = 2, &objects.VRF{NetboxObject: objects.NetboxObject{ID: 7}}
	duplicate := global
	duplicate.ID = 3
	tests := []struct {
		name    string
		ips     []objects.IPAddress
		desired *objects.IPAddress
		wantID  int
		wantErr bool
	}{
		{"global table", []objects.IPAddress{vrf, global}, &global, 1, false},
		{"explicit VRF", []objects.IPAddress{global, vrf}, &vrf, 2, false},
		{"other VRF only", []objects.IPAddress{vrf}, &global, 0, false},
		{"ambiguous", []objects.IPAddress{global, duplicate}, &global, 0, true},
	}
	for _, tt := range tests {
		t.Run(tt.name, func(t *testing.T) {
			got, err := chooseReusableIPAddress(tt.ips, tt.desired)
			if (err != nil) != tt.wantErr {
				t.Fatalf("unexpected error: %v", err)
			}
			if (got == nil && tt.wantID != 0) || (got != nil && got.ID != tt.wantID) {
				t.Errorf("expected ID %d, got %v", tt.wantID, got)
			}
		})
	}
}

func TestIPAddressReassignmentOwnership(t *testing.T) {
	ssotTag := &objects.Tag{ID: 1, Name: constants.SsotTagName}
	for _, test := range []struct {
		name    string
		managed bool
		want    string
	}{
		{"unmanaged assignment", false, "outside SSOT management"},
		{"higher priority source", true, "source priority prevents reassignment"},
	} {
		t.Run(test.name, func(t *testing.T) {
			existing := &objects.IPAddress{
				NetboxObject: objects.NetboxObject{ID: 42,
					CustomFields: map[string]interface{}{"source": "authoritative"}},
				Address: "192.0.2.1/32", AssignedObjectType: constants.ContentTypeVirtualizationVMInterface,
				AssignedObjectID: 10,
			}
			if test.managed {
				existing.AddTag(ssotTag)
			}
			desired := &objects.IPAddress{
				NetboxObject: objects.NetboxObject{CustomFields: map[string]interface{}{"source": "secondary"}},
				Address:      "192.0.2.1/32", AssignedObjectType: constants.ContentTypeVirtualizationVMInterface,
				AssignedObjectID: 20,
			}
			nbi := &NetboxInventory{SsotTag: ssotTag, SourcePriority: map[string]int{"authoritative": 0, "secondary": 1}}
			_, err := nbi.reassignIPAddress(context.Background(), existing, desired)
			if err == nil || !strings.Contains(err.Error(), test.want) {
				t.Fatalf("expected %q before any API calls, got %v", test.want, err)
			}
		})
	}
}
