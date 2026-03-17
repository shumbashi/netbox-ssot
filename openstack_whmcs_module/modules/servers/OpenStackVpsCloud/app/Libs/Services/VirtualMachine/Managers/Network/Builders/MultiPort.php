<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Network\Builders;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;

class MultiPort extends Networking
{
    public function build(): void
    {
        for ($i = 0; $i < $this->addressAmount; $i++) {
            $port = $this->createPort();
            $this->vm->addCreationPortID($port['id']);
        }
    }

    public function rebuild()
    {
        $ports = Api::getInstance()->network()->listPorts([
            'network_id' => $this->fixedNetworkId,
            'device_owner' => 'compute:nova',
            'device_id' => $this->vm->getUUID(),
        ]);

        $ips = $this->addressAmount - count($ports ?? []);
        if ($ips == 0) {
            return;
        }

        if ($ips > 0) {
            for ($i = 0; $i < $ips; $i++) {
                $port = $this->createPort();
                Api::getInstance()->compute()->createInterface($this->vm->getUUID(), $port['id']);
            }

            return;
        }

        if ($ips < 0) {

            /*Filter dedicated ip*/
            $dedicatedFound = false;
            foreach ($ports as $key => $port) {
                foreach ($port['fixed_ips'] as $fixedIp) {
                    if ($fixedIp['ip_address'] == $this->dedicatedIp) {
                        $dedicatedFound = true;
                        unset($ports[$key]);
                        break 2;
                    }
                }
            }

            /*Find dedicated by floating ip*/
            if (!$dedicatedFound && $this->floatingNetworkId) {
                $dedicatedFloatingIps = null;
                try {
                    $dedicatedFloatingIps = Api::getInstance()->network()->getFloatingIps([
                        'floating_network_id' => $this->floatingNetworkId,
                        'floating_ip_address' => $this->dedicatedIp,
                    ]);
                }
                catch (\Exception $exception) {}

                if (is_array($dedicatedFloatingIps) && $dedicatedFloatingIp = reset($dedicatedFloatingIps)) {
                    foreach ($ports as $key => $port) {
                        if ($port['id'] == $dedicatedFloatingIp['port_id']) {
                            unset($ports[$key]);
                            break;
                        }
                    }
                }
            }

            /*Grab ports for removal*/
            $deletionPorts = array_slice($ports, $ips);
            foreach ($deletionPorts as $port) {
                if ($this->floatingNetworkId) {
                    $floatingIps = Api::getInstance()->network()->getFloatingIps([
                        'floating_network_id' => $this->floatingNetworkId,
                        'port_id' => $port['id'],
                    ]);

                    foreach ($floatingIps as $floatingIp) {
                        Api::getInstance()->network()->deleteFloatingIP($floatingIp['id']);
                    }
                }

                Api::getInstance()->compute()->deleteInterface($this->vm->getUUID(), $port['id']);
                Api::getInstance()->network()->deletePort($port['id']);
            }

            return;
        }
    }

    protected function createPort()
    {
        $port = Api::getInstance()->network()->createPort([
            'network_id' => $this->fixedNetworkId,
        ]);

        if (!$this->floatingNetworkId) {
            return $port;
        }

        foreach ($port['fixed_ips'] as $fixedIp) {
            if (!filter_var($fixedIp['ip_address'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                continue;
            }

            Api::getInstance()->network()->createFloatingIp([
                'floating_network_id' => $this->floatingNetworkId,
                'port_id' => $port['id'],
                'fixed_ip_address' => $fixedIp['ip_address']
            ]);
        }

        return $port;
    }

    public function terminate()
    {
        // TODO: Implement terminate() method.
    }
}