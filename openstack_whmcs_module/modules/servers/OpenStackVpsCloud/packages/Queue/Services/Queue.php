<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Services;

use ModulesGarden\OpenStackVpsCloud\Packages\Queue\DatabaseQueue;

class Queue
{
    public function push($job, $data = '', $queue = 'default', $parentId = null, $relType = null, $relId = null, $relCustom = null)
    {
        $queueDB = \ModulesGarden\OpenStackVpsCloud\Core\DependencyInjection::get(DatabaseQueue::class);
        return $queueDB->push($job, $data, $queue, $parentId, $relType, $relId, $relCustom);
    }
}