<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;


use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Factories\BackupFactory;
use ModulesGarden\OpenStackVpsCloud\App\Repositories\BackupsRepository;

class DeleteBackups extends JobsManager
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var array
     */
    protected array $backupIds = [];

    /**
     * @var ProductConfiguration
     */
    protected $productConfig = null;


    /**
     * @param int $hid
     * @param int|null $pid
     * @param array|null $backupIds
     * @return bool|void
     * @throws \Exception
     */
    public function handle(int $hid = 0, int $pid = null, array $backupIDs = null)
    {
        $this->params = WhmcsParamsHelper::getWhmcsParamsByHostingId($hid);
        $this->backupIds = $backupIDs;
        $this->productConfig = new ProductConfiguration($hid);

        return $this->runTask($this->params, $pid);
    }

    /**
     * @return bool
     */
    public function runTaskAction()
    {
        $backupsManager = BackupFactory::getBackupManager($this->productConfig, $this->productConfig->getCustomFields()['vmID']);
        $backupsManager->deleteBackups($this->backupIds);

        (new BackupsRepository())->massDelete($this->backupIds);

        return true;
    }
}