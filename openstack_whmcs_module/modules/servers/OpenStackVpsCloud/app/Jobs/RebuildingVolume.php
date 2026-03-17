<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Jobs\Abstracts\AbstractVmStageJobManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductCustomFields;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\VmTerminateProcessor;
use ModulesGarden\OpenStackVpsCloud\App\Models\Job;
use ModulesGarden\OpenStackVpsCloud\Core\Queue\Models\Job as CoreJob;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Decorators\ScheduledTasksDecorator;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Status;

class RebuildingVolume extends AbstractVmStageJobManager
{
    const REBUILDING_VOLUME         = 'RebuildingVolume';
    const BUILDING_VM               = 'buildingVm';
    const CREATION_INSTANCE         = 'creationInstance';
    const SETTING_VM_DETAILS        = 'settingVmDetails';
    const TERMINATE_OLD_VM          = 'terminateOldVm';
    const SEND_EMAIL                = 'sendEmail';

    const FIRST_ACTION              = self::BUILDING_VM;

    /**
     * @param string|null $stage
     * @param array $additionalAdditionalData
     * @return bool
     */
    public function runStageProcess(string $stage = null, array $data = [])
    {
        switch ($stage)
        {
            case self::BUILDING_VM:
                $newJob = $this->addTask(BuildingVM::class, $this->parseArguments(self::REBUILDING_VOLUME, $data));
                $this->addDataToJob(['stage' => self::CREATION_INSTANCE, 'jobID' => $newJob->id]);
                return $this->postpone();
            case self::CREATION_INSTANCE:
                $newJob = $this->addTask(CreationInstance::class, $this->parseArguments(self::REBUILDING_VOLUME, $data));
                $this->addDataToJob(['stage' => self::SETTING_VM_DETAILS, 'jobID' => $newJob->id]);
                return $this->postpone();
            case self::SETTING_VM_DETAILS:
                $newJob = $this->addTask(SettingVMDetails::class, $this->parseArguments(self::REBUILDING_VOLUME, $data));
                $this->addDataToJob(['stage' => self::TERMINATE_OLD_VM, 'jobID' => $newJob->id]);
                return $this->postpone();
            case self::TERMINATE_OLD_VM:
                if (!$this->shouldStartTerminate())
                {
                    return $this->postpone();
                }
                $newJob = $this->addTask(TerminationVM::class, $this->parseArguments(self::REBUILDING_VOLUME, $data));
                $this->addDataToJob(['stage' => self::SEND_EMAIL, 'jobID' => $newJob->id]);
                return $this->postpone();
            case self::SEND_EMAIL:
                $this->addTask(SendEmail::class, $this->parseArguments(self::REBUILDING_VOLUME, $data));
                return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    private function shouldStartTerminate(): bool
    {
        $unfinishedCreateInterfacesJob = (new Job())->byRelType(Job::TYPE_HOSTING)
            ->byServiceID($this->model->rel_id)
            ->byJob(ScheduledTasksDecorator::TASK_CREATING_INTERFACES)
            ->where('status', '<>', Status::FINISHED)
            ->first();

        if ($unfinishedCreateInterfacesJob)
        {
            return false;
        }

        return true;
    }

    public function runClearProcess(string $job, array $data = [])
    {
        $vmArray = Arr::get($data, 'clearStageData.vm', false);
        if (!$vmArray) {
            return;
        }

        $vm = VPSModel::fromArray($vmArray);

        $productCustomFields = new ProductCustomFields($this->params['pid'], $this->params['serviceid']);
        $errorMessage = '';

        /**
         * Delete flavor if exist
         */
        if ($productCustomFields->getCustomFieldsValue('privateFlavor'))
        {
            try {

                $vm->getFlavor()->delete();

                $productCustomFields->updateFieldValue('privateFlavor', '');
            }
            catch (\Exception $exception)
            {
                $errorMessage .= '#Flavor Delete: ' . $exception->getMessage();
            }
        }

        /**
         * Delete SSH Key if exist
         */
        if ($vm->getSshKey())
        {
            try {
                $vm->getSshKey()->delete();
            }
            catch (\Exception $exception)
            {
                $errorMessage .= '#SSH Key Delete:  ' . $exception->getMessage();
            }
        }

        /**
         * Delete Block Devices if exist
         */
        if ($vm->getBlockDevices())
        {
            try {
                $this->addTask(
                    DeleteBlockDevices::class,
                    [
                        'devices' => $vm->getBlockDevices(),
                        'hid' => $this->params['serviceid'],
                        'pid' => $this->params['pid'],
                    ]);
            }
            catch (\Exception $exception)
            {
                $errorMessage .= '#Block Device Delete:  ' . $exception->getMessage();
            }
        }


        /**
         * Delete VM if exist
         */
        if ($vm->getUUID())
        {
            try
            {
                $terminateProcessor = new VmTerminateProcessor($vm, $this->params['serviceid']);

                $terminateProcessor->deleteInstance();
                $terminateProcessor->deleteVmID();

            }
            catch (\Exception $exception)
            {
                throw new \Exception('#VM Delete:  ' . $exception->getMessage());
            }
        }


        if (!empty($errorMessage))
        {
            throw new \Exception($errorMessage);
        }
    }

    /**
     * Returns the first action of the rebuilding volume process
     *
     * @return string
     */
    public function getFirstAction()
    {
        return self::FIRST_ACTION;
    }
}