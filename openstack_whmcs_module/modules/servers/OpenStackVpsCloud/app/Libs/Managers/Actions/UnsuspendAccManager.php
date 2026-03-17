<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\Actions;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;

class UnsuspendAccManager extends BaseManager
{
    public function unsuspend()
    {
        $vm = $this->loadVm();

        if (VPSModel::STATUS_ACTIVE !== $vm->getStatus())
        {
//            Api::getInstance()->compute()->unlockVPS($vm->getUUID());
            Api::getInstance()->compute()->resumeVPS($vm->getUUID());
        }
    }
}