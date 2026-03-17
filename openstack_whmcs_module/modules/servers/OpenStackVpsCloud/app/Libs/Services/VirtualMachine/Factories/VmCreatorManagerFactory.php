<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Factories;


use Exception;
use ModulesGarden\OpenStackVpsCloud\App\Http\Actions\CreateAccount;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\ChangingPackage;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\CreationVm;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\RebuildingVolume;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\RestoringVolumeProcess;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\Actions\ChangePackageManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\Actions\CreateAccManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\RebuildVolumeManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\RestoreSnapshotManager;

/**
 * Class CreationVmManagerFactory
 * @package ModulesGarden\OpenStackVpsCloud\App\Libs\Factories
 */
class VmCreatorManagerFactory
{
    /**
     * @param string $actionType
     * @param array $params
     * @param array $additionalVars
     * @return ChangePackageManager|CreateAccManager|RestoreSnapshotManager|RebuildVolumeManager
     * @throws Exception
     */
    public static function getManager(string $actionType, array $params = [], array $additionalVars = [])
    {
        switch ($actionType)
        {
            case CreationVm::CREATION_VM:
                $params['action'] = CreateAccount::CREATE_ACCOUNT;
                return new CreateAccManager($params);
            case ChangingPackage::CHANGING_PACKAGE:
                return new ChangePackageManager($params);
            case RestoringVolumeProcess::RESTORING_VOLUME_PROCESS:
                return new RestoreSnapshotManager($params['customfields']['vmID'], $params, $additionalVars['newVolumeID']);
            case RebuildingVolume::REBUILDING_VOLUME:
                return new RebuildVolumeManager($params['customfields']['vmID'], $params, $additionalVars);
        }

        throw new Exception('Unavailable Action Type');
    }
}