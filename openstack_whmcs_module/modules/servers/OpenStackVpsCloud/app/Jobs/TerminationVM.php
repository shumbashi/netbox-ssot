<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Factories\VmTerminateManager;

class TerminationVM extends JobsManager
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var string
     */
    protected $actionType;

    /**
     * Exists during rebuild or restore vm with volume
     *
     * @var string
     */
    protected $oldVmId;

    /**
     * @param int $hid
     * @param int|null $pid
     * @param string|null $actionType
     * @param array $data
     * @return bool|void
     * @throws \Exception
     */
    public function handle(int $hid = 0, int $pid = null, string $actionType = null, array $data = [])
    {
        $this->params     = WhmcsParamsHelper::getWhmcsParamsByHostingId($hid);
        $this->actionType = $actionType;
        $this->oldVmId    = $data['oldVmId'] ? : null;

        return $this->runTask($this->params, $pid);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function runTaskAction()
    {
        $manager = VmTerminateManager::getManager($this->actionType, $this->params);
        $manager->runDeleteVm($this->oldVmId);

        return true;
    }


}