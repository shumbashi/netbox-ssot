<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Providers;

use ModulesGarden\OpenStackVpsCloud\App\Traits\ApiTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Filters\SnapshotsFilter as SnapshotFilter;

class SnapshotsDataTableProvider
{
    use ApiTrait;

    public function loadData()
    {
        $snapshots = (new SnapshotFilter($this->api->volume()->listSnapshots()))
            ->filterByServiceId(Params::get('serviceid'))
            ->get();

        $this->data = $snapshots;

        foreach ($this->data as &$snapshot) {
            $snapshot['created'] = date('Y-m-d H:i:s', strtotime($snapshot['created']));
            $snapshot['size'] = $snapshot['size'] . ' GiB';
            unset($snapshot['metadata']);
        }

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }
}