<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\PostCreationSetters\PostRestoreSnapshotSetter;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\VmService;

class SendEmail extends JobsManager
{
    /**
     * @var int
     */
    protected $pid;

    /**
     * @var array
     */
    protected $params;

    /**
     * @param int $hid
     * @param int|null $pid
     * @return bool|void
     * @throws \Exception
     */
    public function handle(int $hid = 0, int $pid = null, string $actionType = '', array $data = [])
    {
        $this->pid    = $pid;
        $this->params = WhmcsParamsHelper::getWhmcsParamsByHostingId($hid);

        return $this->runTask($this->params, $pid);
    }

    /**
     * @return bool
     */
    public function runTaskAction()
    {
        $vmService = new VmService($this->params);
        $vm        = $vmService->getVm();
        if($vm->getStatus() == VPSModel::STATUS_ACTIVE)
        {
            $setter = new PostRestoreSnapshotSetter($this->params, $vm);
            $setter->sendRebuildEmailIfSet();
            return true;
        }

        return $this->postpone();
    }
}