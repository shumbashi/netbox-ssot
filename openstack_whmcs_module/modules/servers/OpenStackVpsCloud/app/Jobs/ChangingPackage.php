<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Jobs\Abstracts\AbstractVmStageJobManager;

class ChangingPackage extends AbstractVmStageJobManager
{
    const CHANGING_PACKAGE   = 'changingPackage';
    const BUILDING_VM        = 'buildingVM';
    const SETTING_VM_DETAILS = 'settingVmDetails';

    const FIRST_ACTION       = self::BUILDING_VM;

    /**
     * @param string|null $stage
     * @param array $data
     * @return bool|mixed
     */
    public function runStageProcess(string $stage = null, array $data = [])
    {
        switch ($stage)
        {
            case self::BUILDING_VM:
                $newJob = $this->addTask(BuildingVM::class, $this->parseArguments(self::CHANGING_PACKAGE, $data));
                $this->addDataToJob(['stage' => self::SETTING_VM_DETAILS, 'jobID' => $newJob->id]);
                return $this->postpone();

            case self::SETTING_VM_DETAILS:
                $this->addTask(SettingVMDetails::class, $this->parseArguments(self::CHANGING_PACKAGE, $data));
                return true;

            default:
                return false;
        }
    }

    /**
     * Returns the first action of the changing package process
     *
     * @return string
     */
    public function getFirstAction()
    {
        return self::FIRST_ACTION;
    }

    public function runClearProcess(string $job, array $data = [])
    {
        // TODO: Implement runClearProcess() method.
    }
}