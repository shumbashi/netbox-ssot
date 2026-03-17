<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Jobs\Abstracts\AbstractVmStageJobManager;

class RestoringVolumeProcess extends AbstractVmStageJobManager
{
    const RESTORING_VOLUME_PROCESS  = 'restoringVolumeProcess';
    const BUILDING_VOLUME           = 'buildingVolume';
    const RESTORING_VOLUME          = 'restoringVolume';
    const BUILDING_VM               = 'buildingVm';
    const CREATION_INSTANCE         = 'creationInstance';
    const SETTING_VM_DETAILS        = 'settingVmDetails';
    const TERMINATE_OLD_VM          = 'terminateOldVm';

    const FIRST_ACTION              = self::BUILDING_VOLUME;

    /**
     * @param string|null $stage
     * @param array $additionalAdditionalData
     * @return bool|mixed
     */
    public function runStageProcess(string $stage = null, array $data = [])
    {
        switch ($stage)
        {
            case self::BUILDING_VOLUME:
                $newJob = $this->addTask(BuildingVolume::class, $this->parseArguments(self::RESTORING_VOLUME_PROCESS, $data));
                $this->addDataToJob(['stage' => self::RESTORING_VOLUME, 'jobID' => $newJob->id]);
                return $this->postpone();
            case self::RESTORING_VOLUME:
                $newJob = $this->addTask(RestoringVolume::class, $this->parseArguments(self::RESTORING_VOLUME_PROCESS, $data));
                $this->addDataToJob(['stage' => self::BUILDING_VM, 'jobID' => $newJob->id]);
                return $this->postpone();
            case self::BUILDING_VM:
                $newJob = $this->addTask(BuildingVM::class, $this->parseArguments(self::RESTORING_VOLUME_PROCESS, $data));
                $this->addDataToJob(['stage' => self::CREATION_INSTANCE, 'jobID' => $newJob->id]);
                return $this->postpone();
            case self::CREATION_INSTANCE:
                $newJob = $this->addTask(CreationInstance::class, $this->parseArguments(self::RESTORING_VOLUME_PROCESS, $data));
                $this->addDataToJob(['stage' => self::SETTING_VM_DETAILS, 'jobID' => $newJob->id]);
                return $this->postpone();
            case self::SETTING_VM_DETAILS:
                $newJob = $this->addTask(SettingVMDetails::class, $this->parseArguments(self::RESTORING_VOLUME_PROCESS, $data));
                $this->addDataToJob(['stage' => self::TERMINATE_OLD_VM, 'jobID' => $newJob->id]);
                return $this->postpone();
            case self::TERMINATE_OLD_VM:
                $this->addTask(TerminationVM::class, $this->parseArguments(self::RESTORING_VOLUME_PROCESS, $data));
                return true;
        }

        return false;
    }

    /**
     * Returns the first action of the restoring volume process
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