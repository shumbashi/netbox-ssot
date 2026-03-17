<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Http\Actions;

use ModulesGarden\OpenStackVpsCloud\App\Jobs\ChangingPackage;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\RebuildingVolume;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders\ImageBuilder;
use ModulesGarden\OpenStackVpsCloud\App\Models\Job;
use ModulesGarden\OpenStackVpsCloud\App\Models\Whmcs\ProductConfigGroups;
use ModulesGarden\OpenStackVpsCloud\App\Models\Whmcs\Upgrade;
use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Status;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Facades\Queue;

/**
 * Class ChangePackage
 *
 * @author <slawomir@modulesgarden.com>
 */
class ChangePackage extends AddonController
{
    public function execute($params = null)
    {
        /**
         * Checking if VM exist
         */
        if (!$params['customfields']['vmID'])
        {
            return 'Custom Field /VM ID/ is empty';
        }

        /*Attempt to build image*/
        try {
            (new ImageBuilder($params))->build();
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

        /**
         * Checking if changing package task already exist
         */
        $jobManager = new JobManager();
        if ($jobManager->isActiveTask($params['serviceid'], ChangingPackage::CHANGING_PACKAGE))
        {
            return 'This task already exist';
        }

        /**
         * Checking if volume type has changed
         */
        $productConfig = new ProductConfiguration($params['serviceid']);
        if($productConfig->getUseVolumes() && !in_array($productConfig->getVolumeType(), $this->getAvaibleVolumeTypes($params, $productConfig)))
        {
            return 'Cannot change volume type';
        }

        try {
            Job::byServiceID($params['serviceid'])
                ->whereIn('status', [Status::WAITING, Status::PENDING, Status::RUNNING, Status::ERROR, ''])
                ->update(['status' => Status::CANCELLED]);

            if($this->shouldRebuild($params)) {
                $isoImageUUID = (new ImageBuilder($params))->build()->getUUID();
                Queue::push(RebuildingVolume::class,
                    [
                        'hid'         => $params['serviceid'],
                        'pid'         => $params['pid'],
                        'data' =>
                            [
                                'newImageId' => $isoImageUUID,
                            ]
                    ],
                    'default',
                    null,
                    'Hosting',
                    $params['serviceid']);
            } else {
                Queue::push(ChangingPackage::class,
                    [
                        'hid' => $params['serviceid'],
                        'pid' => $params['pid'],
                    ],
                    'default',
                    null,
                    'Hosting',
                    $params['serviceid']);
            }

            return 'success';
        }
        catch (\Exception $exception)
        {
            return $exception->getMessage();
        }
    }

    protected function getAvaibleVolumeTypes($params, $productConfig)
    {
        $tenant        = Factory::getTenantAsUser($params, $productConfig->getTenantID());
        $vm            = $tenant->VPS($params['customfields']['vmID']);

        $avaibleTypes = [];
        foreach($vm->getBlockDevices() as $device)
        {
            $avaibleTypes[] = $device->getType();
        }

        return $avaibleTypes;
    }

    /**
     * @return bool|mixed
     */
    protected function shouldRebuild($params)
    {
        $upgradeModel = new Upgrade();
        if (!$upgradeModel->isPending($params['serviceid']) && !$upgradeModel->isPayed($params['serviceid']))
        {
            return false;
        }

        $productConfGroupModel = new ProductConfigGroups();
        $configOptionID = $productConfGroupModel->getConfigurableOptionID('app|', $params['pid']);
        if (!$configOptionID)
        {
            return false;
        }

        return $upgradeModel->hasNewValue($configOptionID, $params['serviceid']);
    }
}
