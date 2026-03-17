<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\SnapshotsManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\BlockDeviceModel;

class DeleteBlockDevices extends JobsManager
{
    protected array $devicesToDelete = [];

    /**
     * @param array $devices
     * @param int $hid
     * @param int|null $pid
     * @return bool|void
     * @throws \Exception
     */
    public function handle(array $devices = [], int $hid = 0, int $pid = null)
    {
        $this->params = WhmcsParamsHelper::getWhmcsParamsByHostingId($hid);

        foreach ($devices as $id => $device) {
            $this->devicesToDelete[$id] = BlockDeviceModel::fromArray($device);
        }

        return $this->runTask($this->params, $pid);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function runTaskAction()
    {
        $snapshotManager = new SnapshotsManager();
        foreach ($this->devicesToDelete as $uuid => $device)
        {
            $deviceDetails = $device->setDetails();
            if (!$deviceDetails)
            {
                unset($this->devicesToDelete[$uuid]);
                continue;
            }

            if ($this->isInUse($device))
            {
                $this->log->info(sprintf('Task postponed due to block device %s being in use.', $device->getUUID()));
                return $this->postpone();
            }

            $snapshotManager->deleteAllByVolumeId($uuid);
        }

        $this->deleteDevices();
        return true;
    }

    /**
     * Delete device process
     */
    protected function deleteDevices()
    {
        foreach ($this->devicesToDelete as $device)
        {
            $device->delete();
        }
    }

    /**
     * @param BlockDeviceModel $device
     * @return bool
     */
    protected function isInUse(BlockDeviceModel $device)
    {
        return BlockDeviceModel::STATUS_IN_USE == $device->getStatus();
    }
}