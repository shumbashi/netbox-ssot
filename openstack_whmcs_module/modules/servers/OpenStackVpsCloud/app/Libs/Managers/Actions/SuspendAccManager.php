<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\Actions;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;

class SuspendAccManager extends BaseManager
{
    public function suspend()
    {
        $vm = $this->loadVm();

        if (VPSModel::STATUS_ACTIVE == $vm->getStatus())
        {
//            Api::getInstance()->compute()->pauseVPS($vm->getUUID());
            Api::getInstance()->compute()->suspendVPS($vm->getUUID());
        }
    }
}