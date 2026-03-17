<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Network;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;

class PoolManager
{
    public function findSubnetsWithFreeIps(string $projectId, string $networkId, int $requiredFreeIps): array
    {
        $subnets = Api::getInstance()->network()->listSubnets([
            'network_id' => $networkId,
        ]) ? : [];

        $ports = Api::getInstance()->network()->listPorts([
            'network_id' => $networkId,
        ]) ?: [];

        $totalAvailableIps = 0;
        $selectedSubnets = [];

        foreach ($subnets as $subnet) {

            if (!$subnet['enable_dhcp'] || $subnet['ip_version'] !== 4) {
                continue;
            }

            $subnetIps = 0;

            foreach ($subnet['allocation_pools'] as $pool) {
                $subnetIps += (ip2long($pool['end']) - ip2long($pool['start']) + 1);
            }

            foreach ($ports as $port) {
                $subnetIps -= count(array_filter($port['fixed_ips'], fn ($ip) => $ip['subnet_id'] === $subnet['id']));
            }

            if ($subnetIps > 0) {
                $selectedSubnets[] = ['subnet_id' => $subnet['id'], 'available_ips' => $subnetIps];
                $totalAvailableIps += $subnetIps;
                if ($totalAvailableIps >= $requiredFreeIps) {
                    break;
                }
            }
        }

        if ($totalAvailableIps < $requiredFreeIps) {
            throw new \Exception("No sufficient subnets found with at least $requiredFreeIps free IPs.");
        }

        return $selectedSubnets;
    }
}