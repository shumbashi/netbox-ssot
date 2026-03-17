<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\ErrorsManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Factories\VmCreatorManagerFactory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\ScheduledBackupsManager;

class SettingVMDetails extends JobsManager
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var VPSModel
     */
    protected $vm;

    /**
     * @var string
     */
    protected $actionType;

    /**
     * @var bool
     */
    protected $emailSent;

    /**
     * @param int $hid
     * @param int|null $pid
     * @param string|null $actionType
     * @param array $data
     * @return bool|void
     * @throws \Exception
     */
    public function handle(int $hid = 9, int $pid = null, string $actionType = null, array $data = [])
    {
//        if ($this->isParentJobStatusWaiting($data['parentId']) && in_array($actionType, [CreationVm::CREATION_VM, ChangingPackage::CHANGING_PACKAGE]))
//        {
//            return $this->postpone();
//        }

        $this->params = WhmcsParamsHelper::getWhmcsParamsByHostingId($hid);
        $this->vm = VPSModel::fromArray($data['vm']);
        $this->emailSent = $data['emailSent'] ?? false;
        $this->actionType = $actionType;

        return $this->runTask($this->params, $pid);

    }

    /**
     * @return bool
     * @throws Exception
     * @throws \Exception
     */
    public function runTaskAction()
    {

        $this->vm->setDetails();

        if ($this->vm->getStateTask())
        {
            return $this->postpone();
        }

        ErrorsManager::checkVmStatusErrors($this->vm);

        try {
            $settingVmDetailsManager = VmCreatorManagerFactory::getManager($this->actionType, $this->params);
            $params = array_merge($this->params, ['emailSent' => $this->emailSent]);
            $settingVmDetailsManager->runSetVmDetailsAfterCreate($this->vm, $params);
            $this->setScheduledBackupsIfEnable();
        } catch (\Exception $exception) {
            $this->addDataToJob(array_merge($this->jobData['data'], $settingVmDetailsManager->getVarsForNextStage()));
            throw $exception;
        }

        $this->addDataToJob($settingVmDetailsManager->getVarsForNextStage());

        return true;
    }

    public function setScheduledBackupsIfEnable()
    {
        if($this->productConfig->getScheduledBackups() && !$this->productConfig->getUseVolumes())
        {
            $this->checkMinimalTimeBetweenBackups();
            $this->params            = WhmcsParamsHelper::getWhmcsParamsByHostingId($this->params['serviceid']);
            $interval                = $this->productConfig->getMinimalTimeBetweenBackups();
            $scheduledBackupsManager = new ScheduledBackupsManager($this->params['customfields']['vmID'], $this->params);
            $scheduledBackupsManager->setScheduledBackups($interval);
        }
    }

    public function checkMinimalTimeBetweenBackups()
    {
        if (empty($this->productConfig->getMinimalTimeBetweenBackups()))
        {
            throw new \Exception('Time between backups cannot be empty');
        }
    }
}