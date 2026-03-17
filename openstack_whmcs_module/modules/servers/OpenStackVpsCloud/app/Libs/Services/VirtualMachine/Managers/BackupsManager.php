<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Decorators\BackupsDecorator;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Filters\BackupsFilter;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\BackupInterface;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\BackupModel;
use ModulesGarden\OpenStackVpsCloud\App\Models\Settings;
use ModulesGarden\OpenStackVpsCloud\App\Models\Backups;
use ModulesGarden\OpenStackVpsCloud\App\Traits\ApiTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;

class BackupsManager extends BaseVpsManager implements BackupInterface
{
    use TranslatorTrait;
    use ApiTrait;

    const TIME_BETWEEN_BACKUPS = 'timeBetweenBackups';

    public function __construct(string $vmID, array $params = [])
    {
        $this->whmcsParams = $params;

        if (empty($this->whmcsParams))
        {
            $this->whmcsParams = WhmcsParamsHelper::getWhmcsParamsByVmId($vmID);
        }

        $this->productConfig = new ProductConfiguration($this->whmcsParams['serviceid']);
        $this->vmId = $vmID;
    }

    public function createBackup(string $backupName)
    {
        if (!$this->canUserCreateBackup())
        {
            throw new \Exception($this->translate('backup_limit_reached'));
        }

        $backupList = $this->getBackups();
        foreach ($backupList as $backup)
        {
            if ($backup['status'] == 'creating') {
                throw new \Exception($this->translate('another_backup_creating'));
            }
        }

        $rotation = $this->productConfig->getBackupsFilesLimit() == '-1' ? 9000 : (int)$this->productConfig->getBackupsFilesLimit();
        if(count($backupList) >= $rotation)
        {
            $this->deleteBackups($this->getOldestNotPinnedBackupID($backupList));
        }

        $this->api->compute()->createBackup($this->vmId, $backupName, BackupModel::TYPE_WEEKLY, $rotation);
    }

    protected function getOldestNotPinnedBackupID(array $backupList)
    {
        $backupPinned = $this->getBackupsPinnedStatus($backupList);
        $oldest = null;

        foreach ($backupList as $backup) {
            if (!empty($backupPinned[$backup['id']])) {
                continue;
            }

            // Pick the first not-pinned, or the oldest among not-pinned
            if ($oldest === null || strtotime($backup['created']) < strtotime($oldest['created'])) {
                $oldest = $backup;
            }
        }

        if ($oldest === null) {
            throw new \Exception($this->translate('unable_to_rotate_all_backups_protected'));
        }

        return [$oldest['id']];
    }

    protected function getBackupsPinnedStatus($backups)
    {
        $ids = [];
        foreach($backups as $backup)
        {
            $ids[] = $backup['id'];
        }

        $rows         = Backups::whereIn('backupID', $ids)->get();
        $backupPinned = [];
        foreach($rows as $row)
        {
            $backupPinned[$row->backupID] = $row->pinned;
        }

        return $backupPinned;
    }

    /**
     * @return bool
     * @throws OSException
     */
    protected function canUserCreateBackup()
    {
        $numberOfExistingBackups = count($this->getBackups());
        if (!$this->productConfig->getBackupRouting() && $this->productConfig->getBackupsFilesLimit() != '-1'
            && $numberOfExistingBackups >= (int)$this->productConfig->getBackupsFilesLimit())
        {
            return false;
        }

        return true;
    }

    /**
     * @param string $id
     * @param string $adminPassword
     * @throws Exception
     */
    public function restoreBackup(string $id, string $adminPassword)
    {
        /*Note: this is being rebuilt without specifying user data*/
        $vm = $this->api->compute()->getVPSDetails($this->vmId);
        $this->api->compute()->rebuild($vm['id'], $vm['name'], $adminPassword, $id, $vm['customScript']);
    }

    public function deleteBackups(array $backupsID = [])
    {
       $backups = (new BackupsFilter($this->api->image()->listBackups()))
            ->filterBySource($this->vmId)
            ->filterIdsIn($backupsID)
            ->get();

        foreach ($backups as $backup)
        {
            $this->api->image()->deleteImage($backup['id']);
        }
    }


    public function getBackups()
    {
       return (new BackupsFilter($this->api->image()->listBackups()))
            ->filterBySource($this->vmId)
            ->get();
    }

    /**
     * @return string
     */
    public function getBackupName()
    {
        return BackupsDecorator::decorateName();
    }


    public function getTimeInterval(): int
    {
        $timeInterval = Settings::byServiceID($this->whmcsParams['serviceid'])
            ->bySetting(self::TIME_BETWEEN_BACKUPS)
            ->first()
            ->value;

        return (int)$timeInterval;
    }

    public function deleteAllBackups(): void
    {
        foreach ($this->getBackups() as $backup)
        {
            $this->api->image()->deleteImage($backup['id']);
        }
    }
}