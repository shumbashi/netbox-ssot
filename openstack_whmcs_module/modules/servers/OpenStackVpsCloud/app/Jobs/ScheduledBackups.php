<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use Carbon\Carbon;
use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Factories\BackupFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Status;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Facades\Queue;

class ScheduledBackups extends JobsManager
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var string
     */
    protected $vmID;


    /**
     * @param int $hid
     * @param int|null $pid
     * @param string|null $vmID
     * @return bool|void
     * @throws \Exception
     */
    public function handle(int $hid = 0, int $pid = null, string $vmID = null)
    {
        $this->params    = WhmcsParamsHelper::getWhmcsParamsByHostingId($hid);
        $this->vmID   = $this->params['customfields']['vmID'];

        return $this->runTask($this->params, $pid);
    }

    /**
     * @return bool|string
     */
    public function runTaskAction()
    {
        $backupsManager = BackupFactory::getBackupManager(new ProductConfiguration($this->params['serviceid']), $this->vmID);
        $backupsManager->createBackup($backupsManager->getBackupName());

        $job = Queue::push(ScheduledBackups::class, [
                'hid' => $this->params['serviceid'],
                'pid' => $this->params['pid']
            ],
        'default',
        null,
        'Hosting',
        $this->params['serviceid']);

        if ($backupsManager->getTimeInterval()) {
            $job->retry_after = Carbon::now()->addSeconds($backupsManager->getTimeInterval() * 3600)->format('Y-m-d H:i:s');
            $job->status = Status::WAITING;
            $job->save();
        }

        return true;
    }
}