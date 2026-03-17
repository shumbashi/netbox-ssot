<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;

interface BuildingVmContext
{
    /**
     * @return VPSModel
     */
    public function runBuildVm();
}