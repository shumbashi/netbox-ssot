<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Network;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;

class IpManager
{
    protected ?VPSModel $vm = null;

    public function __construct($vm)
    {
        $this->vm = $vm;
    }

    protected function buildFixedIps(array $subnets, int $ipsCount)
    {
        $fixedIps = [];
        foreach ($subnets as $subnet) {
            $ips = min($ipsCount, $subnet['available_ips']);
            for ($i = 0; $i < $ips; $i++) {
                $fixedIps[] = ['subnet_id' => $subnet['subnet_id']];
            }

            $ipsCount -= $ips;
            if ($ipsCount <= 0) {
                break;
            }
        }

        return $fixedIps;
    }

    public function addFixedIpsToPort(string $portId, int $ips)
    {
        $port = Api::getInstance()->network()->getPort($portId);

        $poolManager = new PoolManager();

        $subnets = $poolManager->findSubnetsWithFreeIps($this->vm->getTenantID(), $port['network_id'], $ips);

        $newIps = $this->buildFixedIps($subnets, $ips);

        return Api::getInstance()->network()->updatePort($port['id'], [
            'fixed_ips' => array_values(array_merge($port['fixed_ips'], $newIps))
        ]);
    }
}