<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;

class NetworkInterfacesManager extends BaseVpsManager
{
    public function deleteIpFromPort(string $portId, string $fixedIp, bool $deleteFloating = false)
    {
        if ($this->vm->getStatus() !== VPSModel::STATUS_ACTIVE)
        {
            throw new \Exception('The Server should be an active when you tried to detach an interface.');
        }

        $port = Api::getInstance()->network()->getPort($portId);

        if ($deleteFloating) {
            $floatingIps = Api::getInstance()->network()->getFloatingIps([
                'port_id' => $port['id'],
                'fixed_ip_address' => $fixedIp
            ]);

            if ($floatingIp = reset($floatingIps)) {
                Api::getInstance()->network()->deleteFloatingIP($floatingIp['id']);
            }
        }

        if (count($port['fixed_ips']) > 1) {
            $newIps = array_filter($port['fixed_ips'], function ($portIp) use ($fixedIp) {
                return $portIp['ip_address'] !== $fixedIp;
            });

            Api::getInstance()->network()->updatePort($port['id'], [
                'fixed_ips' => array_values($newIps),
            ]);
        } else {
            Api::getInstance()->compute()->deleteInterface($this->vm->getUUID(), $port['id']);
            Api::getInstance()->network()->deletePort($port['id']);
        }
    }

    public function getServiceIps(?string $dedicatedIp)
    {
        $interfaces = Api::getInstance()->compute()->listInterfaces($this->vm->getUUID());

        $dedicatedPair = [];
        $assigned = [];
        foreach ($interfaces['interfaceAttachments'] as $attachment) {
            $port = Api::getInstance()->network()->getPort($attachment['port_id']);
            foreach ($port['fixed_ips'] as $fixedIp) {
                $floatingIps = Api::getInstance()->network()->getFloatingIPs([
                    'port_id' => $port['id'],
                    'fixed_ip_address' => $fixedIp['ip_address']
                ]);

                $ips = [];
                if ($floatingIp = reset($floatingIps)) {
                    $ips[] = $floatingIp['floating_ip_address'];
                }

                $ips[] = $fixedIp['ip_address'];

                if (!empty($dedicatedIp) && in_array($dedicatedIp, $ips)) {
                    $dedicatedPair = $ips;
                    continue;
                }

                $assigned = array_merge($assigned, $ips);
            }
        }
        if (empty($dedicatedPair)) {
            $ipToSetAsDedicated = $this->getIpToSetAsDedicated($assigned);
        }
        else {
            $ipToSetAsDedicated = array_shift($dedicatedPair);
            $assigned = array_merge($dedicatedPair, $assigned);
        }

        $dedicatedip = $ipToSetAsDedicated && $ipToSetAsDedicated != '' ? $ipToSetAsDedicated : '';
        $assignedips = !empty($assigned) ? implode("\n", $assigned) : '';

        return [
                'dedicatedip' => $dedicatedip,
                'assignedips' => $assignedips
        ];
    }

    /**
     * @param array $ipsList
     * @return string
     */
    protected function getIpToSetAsDedicated(array &$ipsList)
    {
        foreach ($ipsList as $key => $ip)
        {
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            {
                unset($ipsList[$key]);
                return $ip;
            }
        }

        $ip = $ipsList[0];
        unset($ipsList[0]);

        return $ip;
    }
}