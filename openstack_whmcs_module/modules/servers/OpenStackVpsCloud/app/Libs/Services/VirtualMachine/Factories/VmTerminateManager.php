<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Factories;


use Exception;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\RebuildingVolume;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\RestoringVolumeProcess;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\TerminatingAccount;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\Actions\TerminateAccManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\TerminationVmContext;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\RebuildVolumeManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\RestoreSnapshotManager;

class VmTerminateManager
{
    /**
     * @param string $actionType
     * @param array $params
     * @param array $additionalVars
     * @return TerminationVmContext
     * @throws Exception
     */
    public static function getManager(string $actionType, array $params = [], array $additionalVars = [])
    {
        switch ($actionType)
        {
            case RestoringVolumeProcess::RESTORING_VOLUME_PROCESS:
                return new RestoreSnapshotManager($params['customfields']['vmID'], $params);
            case RebuildingVolume::REBUILDING_VOLUME:
                return new RebuildVolumeManager($params['customfields']['vmID'], $params);
            case TerminatingAccount::TERMINATING_ACCOUNT:
                return new TerminateAccManager($params);
        }

        throw new Exception('Unavailable Action Type');
    }
}