<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces;


use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;

interface CreateInstanceContext
{
    /**
     * @param VPSModel $vm
     * @return mixed
     */
    public function runCreateInstance(VPSModel $vm);
}