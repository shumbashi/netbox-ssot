<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\BlockDeviceModel;

class RestoringVolume extends JobsManager
{
    /**
     * @var array
     */
    protected $params;

    protected ?array $data = null;
    /**
     * @param int $hid
     * @param int|null $pid
     * @param string|null $actionType
     * @param array $additionalData
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
        $this->data = $data;

        return $this->runTask($this->params, $pid);
    }

    /**
     * @throws \Exception
     */
    public function runTaskAction()
    {
        $newVolume = Api::getInstance()->volume()->getVolume($this->data['newVolumeID']);

        if(!$this->data['createFromSnapshot'])
        {
            if ($newVolume['status'] !== BlockDeviceModel::STATUS_AVAILABLE || $newVolume['bootable'] !== 'false')
            {
                return $this->postpone();
            }

            Api::getInstance()->volume()->restoreVolume($this->data['restoreBackupId'], $this->data['newVolumeID']);
        }

        $this->addDataToJob(['newVolumeID' => $newVolume['id']]);
    }
}