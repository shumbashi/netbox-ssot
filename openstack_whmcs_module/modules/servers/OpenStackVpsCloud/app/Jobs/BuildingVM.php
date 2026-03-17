<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Factories\VmCreatorManagerFactory;

class BuildingVM extends JobsManager
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
     * @var array
     */
    protected $data;

    /**
     * @param int $hid
     * @param int $pid
     * @param string|null $actionType
     * @param array|null $additionalData
     * @return bool|void
     * @throws \Exception
     */
    public function handle(int $hid = 0, int $pid = null, string $actionType = null, array $data = [])
    {
        if (!$this->isParentJobStatusWaiting($data['parentId']))
        {
            return $this->postpone();
        }

        $this->params         = WhmcsParamsHelper::getWhmcsParamsByHostingId($hid);
        $this->actionType     = $actionType;
        $this->data = $data ? : [];

        return $this->runTask($this->params, $pid);
    }

    /**
     * @throws \Exception
     */
    public function runTaskAction()
    {
        $buildingManager = VmCreatorManagerFactory::getManager($this->actionType, $this->params, $this->data);
        $buildingManager->runBuildVM();

        $vars = $buildingManager->getVarsForNextStage();
        $this->addDataToJob($vars);
    }
}
