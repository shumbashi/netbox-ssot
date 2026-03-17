<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\RestoringVolumeProcess;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Decorators\BackupsDecorator;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Filters\VolumeBackupsFilter;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\BackupInterface;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\BlockDeviceModel;
use ModulesGarden\OpenStackVpsCloud\App\Models\Backups;
use ModulesGarden\OpenStackVpsCloud\App\Models\Settings;
use ModulesGarden\OpenStackVpsCloud\App\Traits\ApiTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Facades\Queue;

class VolumeBackupsManager extends BaseVpsManager implements BackupInterface
{
    use ApiTrait;

    const WHMCS_HOSTING_ID     = 'whmcs-hosting-id';
    const TIME_BETWEEN_BACKUPS = 'timeBetweenBackups';

    use TranslatorTrait;

    protected ?array $allSnapshots = null;

    public function __construct(string $vmID, array $params = [])
    {
        $this->whmcsParams = $params;

        if (empty($this->whmcsParams))
        {
            $this->whmcsParams = WhmcsParamsHelper::getWhmcsParamsByVmId($vmID);
        }

        $this->productConfig = new ProductConfiguration($this->whmcsParams['serviceid']);
        $this->productConfiguration = (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration($this->whmcsParams['packageid']))->get();
        $this->vmId = $vmID;
    }

    public function createBackup(string $backupName)
    {
        $backups = $this->getBackups();
        if (!$this->isWithinLimit($backups))
        {
            throw new \Exception($this->translate('backup_limit_reached'));
        }

        if ($this->isAlreadyCreating($backups))
        {
            throw new \Exception($this->translate('another_backup_creating'));
        }

        if ($this->shouldRotate($backups))
        {
            $rotate = $this->getBackupToRotate($backups);
            if (!$rotate)
            {
                throw new \Exception($this->translate('unable_to_rotate_all_backups_protected'));
            }

            $this->api->volume()->deleteVolumeBackup($rotate['id']);
        }

        $blockDevices = [];
        $devices = $this->api->compute()->getVolumeAttachments($this->vmId);
        foreach ($devices as $volume) {
            $tmp = new BlockDeviceModel(Params::get('customfields.vmID'), null, ['UUID' => $volume['volumeId']]);
            $tmp->setDetails();
            $blockDevices[] = $tmp;
        }

        foreach ($blockDevices as $volume)
        {
            if ($volume->getStatus() == BlockDeviceModel::STATUS_IN_USE)
            {
                $this->api->volume()->createBackup($volume->getUUID(), $backupName, [self::WHMCS_HOSTING_ID => $this->whmcsParams['serviceid']]);
            }
        }
    }

    protected function isWithinLimit(array $backups): bool
    {
        //Rotating backups enabled - no limit
        if ($this->productConfiguration['backupRouting'])
        {
            return true;
        }

        //Infinite number of backups enabled - no limit
        if ($this->productConfiguration['backupsFilesLimit'] < 0)
        {
            return true;
        }

        //Check limit
        return count($backups) < (int)$this->productConfiguration['backupsFilesLimit'];
    }

    protected function shouldRotate(array $backups): bool
    {
        if (!$this->productConfiguration['backupRouting'])
        {
            return false;
        }

        if ($this->productConfiguration['backupsFilesLimit'] < 0)
        {
            return false;
        }

        return count($backups) >= (int)$this->productConfiguration['backupsFilesLimit'];
    }

    protected function isAlreadyCreating(array $backups): bool
    {
        foreach ($backups as $backup)
        {
            if ($backup['status'] == 'creating') {
                return true;
            }
        }

        return false;
    }

    protected function getBackupToRotate(array $backups)
    {
        $protected = Backups::whereIn('backupID', array_column($backups, 'id'))
            ->where('pinned', true)
            ->get()
            ->pluck('backupID')
            ->toArray();

        return (new VolumeBackupsFilter($backups))
            ->filterIdsNotIn($protected)
            ->getOldest();
    }

    public function getBackups(): array
    {
        $allSnapshots = $this->getSnapshots();

        return (new VolumeBackupsFilter($allSnapshots))
            ->filterByServiceId($this->whmcsParams['serviceid'])
            ->get();
    }

    protected function getSnapshots(): array
    {
        if (is_null($this->allSnapshots))
        {
            $this->allSnapshots = $this->api->volume()->listVolumeBackups();
        }

        return $this->allSnapshots;
    }

    public function deleteBackups(array $backupsID = []): void
    {
        $backups = (new VolumeBackupsFilter($this->getSnapshots()))
            ->filterByServiceId($this->whmcsParams['serviceid'])
            ->filterIdsIn($backupsID)
            ->get();

        foreach ($backups as $backup)
        {
            $this->api->volume()->deleteVolumeBackup($backup['id']);
        }
    }

    public function deleteAllBackups(): void
    {
        foreach ($this->getBackupsIDs() as $backupId)
        {
            $this->api->volume()->deleteVolumeBackup($backupId);
        }
    }

    protected function getBackupsIDs(): array
    {
        $backupsIDs = [];

        foreach ($this->getBackups() as $backup)
        {
            $backupsIDs[] = $backup['UUID'];
        }

        return $backupsIDs;
    }

    public function restoreBackup(string $id, string $adminPassword): void
    {
        if (!$this->backupExists($id)) {
            throw new \Exception($this->translate('block_device_not_found'));
        }

        /**
         * Checking if restore backup task already exist
         */
        $jobManager = new JobManager();
        if ($jobManager->isActiveTask($this->whmcsParams['serviceid'], RestoringVolumeProcess::RESTORING_VOLUME_PROCESS)) {
            throw new \Exception($this->translate('backup_restore_task_already_exists'));
        }

        $devices = $this->api->compute()->getVolumeAttachments($this->vmId);
        if (empty($devices))
        {
            throw new \Exception('no_devices');
        }

        $device = reset($devices);
        $blockDevice = new BlockDeviceModel($this->vmId, null, ['UUID' => $device['volumeId']]);
        $blockDevice->setDetails();

        Queue::push(RestoringVolumeProcess::class,
            [
                'hid'            => $this->whmcsParams['serviceid'],
                'pid'            => $this->whmcsParams['pid'],
                'data' => [
                    'restoreBackupId'     => $id,
                    'currentBlockDevice' => [
                            'size' => $blockDevice->getSize(),
                            'name' => $blockDevice->getName()
                    ],
                ]
            ],
            'default',
            null,
            'Hosting',
            $this->whmcsParams['serviceid']);

    }

    protected function backupExists(?string $backupID): bool
    {
        foreach ($this->getBackups() as $backup)
        {
            if ($backup['UUID'] == $backupID)
            {
                return true;
            }
        }

        return false;
    }

    public function getBackupName(): string
    {
        return BackupsDecorator::decorateVolumeBackupName();
    }

    public function getTimeInterval(): int
    {
        $timeInterval = Settings::byServiceID($this->whmcsParams['serviceid'])
            ->bySetting(self::TIME_BETWEEN_BACKUPS)
            ->first()
            ->value;

        return (int)$timeInterval;
    }
}