<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Exceptions\PostponeTaskException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;

class ErrorsManager
{
    /**
     * @param VPSModel $vm
     * @throws Exception
     */
    public static function checkVmStatusErrors(VPSModel $vm)
    {
        if (empty($vm->getStatus()))
        {
            throw new PostponeTaskException('Task postponed, waiting for vm...');
        }

        if ($vm->getStatus() == VPSModel::STATUS_ERROR)
        {
            $data = Api::getInstance()->compute()->getVPSDetails($vm->getUUID());
            throw new \Exception(sprintf('Failed during VM spawning: %s.', $data['error']));
        }
    }

}