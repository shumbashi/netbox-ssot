<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\BlockDeviceModel;

class BuildingVolume extends JobsManager
{
    protected $params;
    protected ?array $data = null;

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

        $this->params             = WhmcsParamsHelper::getWhmcsParamsByHostingId($hid);
        $this->data = $data;

        return $this->runTask($this->params, $pid);
    }

    /**
     * @throws OSException
     * @throws \Exception
     */
    public function runTaskAction()
    {
        $newVolume = $this->createNewVolumne();

        $this->addDataToJob([
            'restoreBackupId' => $this->data['restoreBackupId'],
            'newVolumeID' => $newVolume['id'],
            'createFromSnapshot' => $this->data['createFromSnapshot'] ?? false
        ]);
    }

    private function createNewVolumne()
    {
        if($this->data['createFromSnapshot'] ?? false)
        {
            $newVolume = Api::getInstance()->volume()->createVolumeFromSnapshot($this->data['snapshotID'], $this->params['domain']);
        }
        else
        {
            $newVolume = Api::getInstance()->volume()->createVolume($this->data['currentBlockDevice']['size'], $this->data['currentBlockDevice']['name']);
        }

        return $newVolume;
    }
}