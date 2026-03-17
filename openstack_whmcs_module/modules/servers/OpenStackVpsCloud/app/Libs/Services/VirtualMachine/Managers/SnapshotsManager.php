<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Filters\SnapshotsFilter;
use ModulesGarden\OpenStackVpsCloud\App\Traits\ApiTrait;

class SnapshotsManager
{
    use ApiTrait;

    public function deleteAllByVolumeId($volumeId)
    {
        $snapshots = (new SnapshotsFilter($this->api->volume()->listSnapshots()))
            ->filterByVolumeId($volumeId)
            ->get();

        foreach ($snapshots as $snapshot) {
            $this->api->volume()->deleteSnapshot($snapshot['id']);
        }
    }
}