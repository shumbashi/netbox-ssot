<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums\LoggerMessages;
use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Factories\VmCreatorManagerFactory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\BlockDeviceModel;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Facades\Logger;

class CreationInstance extends JobsManager
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
     * @param int $hid
     * @param int|null $pid
     * @param string|null $actionType
     * @param array $data
     * @return bool|void
     * @throws \Exception
     */
    public function handle(int $hid = 0, int $pid = null, string $actionType = null, array $data = [])
    {
        if (!$this->isParentJobStatusWaiting($data['parentId']))
        {
            return $this->postpone();
        }

        $this->params = WhmcsParamsHelper::getWhmcsParamsByHostingId($hid);
        $this->actionType = $actionType;

        $this->vm = VPSModel::fromArray($data['vm']);

        return $this->runTask($this->params, $pid);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function runTaskAction()
    {

        $productConfig = new ProductConfiguration($this->params['serviceid']);

        if ($productConfig->getUseVolumes())
        {
            foreach ($this->vm->getBlockDevices() as $device)
            {
                $device->setDetails();

                if ($device->getStatus() === BlockDeviceModel::STATUS_ERROR_RESTORING)
                {
                    throw new \Exception(sprintf('Device %s is in error restoring state.', $device->getUUID()));
                }

                if (in_array($device->getStatus(), [BlockDeviceModel::STATUS_CREATING, BlockDeviceModel::STATUS_DOWNLOADING]))
                {
                    $this->log->info(sprintf('Task postponed, awaiting block device %s creation.', $device->getUUID()));
                    return $this->postpone();
                }

                if ($this->actionType == RestoringVolumeProcess::RESTORING_VOLUME_PROCESS && !$device->isBootable())
                {
                    $this->log->info(sprintf('Task postponed, awaiting block device %s creation.', $device->getUUID()));
                    return $this->postpone();
                }

                if ($device->getStatus() !== BlockDeviceModel::STATUS_AVAILABLE)
                {
                    $this->addDataToClearProcess(['vm' => $this->vm]);
                    throw new \Exception(sprintf('You can\'t use this block storage device. Device status: %s.', $device->getStatus()));
                }
            }
        }

        if (!empty($this->vm->getCreationPortsIDs()))
        {
            foreach ($this->vm->getCreationPortsIDs() as $portId) {
                $port = Api::getInstance()->network()->getPort($portId);
                if (!empty($port['device_id'])) {
                    $this->log->info(sprintf('Task postponed due to port %s being in use.', $portId));
                    $this->postpone();
                }
            }
        }

        $creationInstanceManager = VmCreatorManagerFactory::getManager($this->actionType, $this->params);
        try
        {
            $creationInstanceManager->runCreateInstance($this->vm);
        }
        catch (\Exception $exception)
        {
            Logger::critical(LoggerMessages::EXCEPTION, [
                'message' => $exception->getMessage(),
                'stacktrace' => $exception->getTraceAsString(),
            ]);

            $this->addDataToJob(array_merge($this->jobData['data'], $creationInstanceManager->getVarsForNextStage()));
            $this->addDataToClearProcess($creationInstanceManager->getVarsForNextStage());

            throw $exception;
        }

        $this->addDataToJob($creationInstanceManager->getVarsForNextStage());

        return true;
    }


    protected function isVolumeCreated(string $status)
    {
        return !in_array($status, [BlockDeviceModel::STATUS_CREATING, BlockDeviceModel::STATUS_DOWNLOADING]);
    }

}