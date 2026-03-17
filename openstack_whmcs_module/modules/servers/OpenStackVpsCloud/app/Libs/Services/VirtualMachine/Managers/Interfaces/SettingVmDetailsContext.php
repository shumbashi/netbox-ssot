<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces;


use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;

interface SettingVmDetailsContext
{
    public function runSetVmDetailsAfterCreate(VPSModel $vm, array $params);
}