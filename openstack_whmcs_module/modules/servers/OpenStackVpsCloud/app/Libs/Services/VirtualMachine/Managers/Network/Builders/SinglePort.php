<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Network\Builders;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Network\IpManager;

class SinglePort extends Networking
{
    public function build()
    {
       $creationPort = Api::getInstance()->network()->createPort(['network_id' => $this->fixedNetworkId]);

       $port = (new IpManager($this->vm))
           ->addFixedIpsToPort($creationPort['id'], $this->addressAmount - 1);

       $this->vm->setCreationPortsIDs([$port['id']]);

       if (!$this->floatingNetworkId) {
           return;
       }

       foreach ($port['fixed_ips'] as $ip) {
           if (!filter_var($ip['ip_address'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
               continue;
           }

           Api::getInstance()->network()->createFloatingIp([
               'floating_network_id' => $this->floatingNetworkId,
               'port_id' => $port['id'],
               'fixed_ip_address' => $ip['ip_address']
           ]);
       }
    }

    public function rebuild()
    {
        $ports = Api::getInstance()->network()->listPorts([
            'network_id' => $this->fixedNetworkId,
            'device_owner' => 'compute:nova',
            'device_id' => $this->vm->getUUID(),
        ]);

        $port = reset($ports);

        $v4Ips = array_filter($port['fixed_ips'], function ($ip) {
            return filter_var($ip['ip_address'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        });

        $ips = $this->addressAmount - count($v4Ips);
        if ($ips == 0) {
            return;
        }

        if ($ips > 0) {

            $withFloating = array_column($v4Ips, 'ip_address');
            $updatedPort = (new IpManager($this->vm))->addFixedIpsToPort($port['id'], $ips);

            if (!$this->floatingNetworkId) {
                return;
            }

            $fixedWithoutFloating = array_diff(array_column($updatedPort['fixed_ips'], 'ip_address'), $withFloating);
            foreach ($fixedWithoutFloating as $ip) {
                if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    continue;
                }

                Api::getInstance()->network()->createFloatingIp([
                    'floating_network_id' => $this->floatingNetworkId,
                    'port_id' => $port['id'],
                    'fixed_ip_address' => $ip
                ]);
            }

            return;
        }

        if ($ips < 0)
        {
            $ipsForRemoval = array_column($v4Ips, 'ip_address');

            /*Filter fixed ips for dedicated*/
            $dedicatedFound = false;
            if ($this->dedicatedIp) {
                $dedicatedIpKey = array_search($this->dedicatedIp, $ipsForRemoval);
                if ($dedicatedIpKey !== false) {
                    $dedicatedFound = true;
                    unset($ipsForRemoval[$dedicatedIpKey]);
                }
            }

            $floatingIps = [];
            if ($this->floatingNetworkId) {
                $floatingIps = Api::getInstance()->network()->getFloatingIps([
                    'floating_network_id' => $this->floatingNetworkId,
                    'port_id' => $port['id'],
                ]);

                /*Filter floating for dedicated*/
                if ($this->dedicatedIp && !$dedicatedFound) {
                    foreach ($floatingIps as $key => $floatingIp) {
                        if ($floatingIp['floating_ip_address'] !== $this->dedicatedIp) {
                            continue;
                        }

                        unset($floatingIps[$key]);

                        /*Unset related in fixed ips*/
                        if (($fixedIpKey = array_search($floatingIp['fixed_ip_address'], $ipsForRemoval)) !== false) {
                            unset($ipsForRemoval[$fixedIpKey]);
                        }

                        break;
                    }
                }
            }

            /*Grab ips for removal*/
            $ipsForRemoval = array_slice($ipsForRemoval, $ips);

            $newIps = array_filter($port['fixed_ips'], function ($ip) use ($ipsForRemoval) {
                return !in_array($ip['ip_address'], $ipsForRemoval);
            });

            if ($this->floatingNetworkId) {
                /*Filter floatings for removal*/
                $floatingIps = array_filter($floatingIps, function ($ip) use ($ipsForRemoval) {
                    return in_array($ip['fixed_ip_address'], $ipsForRemoval);
                });

                foreach ($floatingIps as $floatingIp) {
                    Api::getInstance()->network()->deleteFloatingIP($floatingIp['id']);
                }
            }

            Api::getInstance()->network()->updatePort($port['id'], [
                'fixed_ips' => array_values($newIps)
            ]);
        }
    }

    public function terminate()
    {
        // TODO: Implement terminate() method.
    }
}