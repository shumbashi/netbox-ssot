<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Jobs\Abstracts\AbstractVmStageJobManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductCustomFields;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\VmTerminateProcessor;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;
use Carbon\Carbon;

class CreationVm extends AbstractVmStageJobManager
{
    const CREATION_VM        = 'creationVM';
    const BUILDING_VM        = 'buildingVm';
    const CREATION_INSTANCE  = 'creationInstance';
    const SETTING_VM_DETAILS = 'settingVmDetails';
    const FIRST_ACTION = self::BUILDING_VM;

    /**
     * @param string|null $stage
     * @param array $data
     * @return bool
     */
    public function runStageProcess(string $stage = null, array $data = [])
    {
        switch ($stage)
        {
            case self::BUILDING_VM:
                $newJob = $this->addTask(BuildingVM::class, $this->parseArguments(self::CREATION_VM, $data));
                $this->addDataToJob(['stage' => self::CREATION_INSTANCE, 'jobID' => $newJob->id]);
                return $this->postpone();
            case self::CREATION_INSTANCE:
                $newJob = $this->addTask(CreationInstance::class, $this->parseArguments(self::CREATION_VM, $data));
                $this->addDataToJob(['stage' => self::SETTING_VM_DETAILS, 'jobID' => $newJob->id]);
                return $this->postpone();
            case self::SETTING_VM_DETAILS:
                $this->addTask(SettingVMDetails::class, $this->parseArguments(self::CREATION_VM, $data));
                return true;
            default:
                return false;
        }
    }

    public function runClearProcess(string $job, array $data = [])
    {
        $vmArray = Arr::get($data, 'clearStageData.vm', false);
        if (!$vmArray) {
            return;
        }

        $productCustomFields = new ProductCustomFields($this->params['pid'], $this->params['serviceid']);
        $errorMessage = '';

        $vm = VPSModel::fromArray($vmArray);

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
     * Returns the first action of the creation VM process
     *
     * @return string
     */
    public function getFirstAction()
    {
        return self::FIRST_ACTION;
    }

}