<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Facades;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\AbstractFacade;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Services\Queue as QueueService;

/**
 * @method static push($job, $data = '', $queue = 'default', $parentId = null, $relType = null, $relId = null, $relCustom = null)
 */
class Queue extends AbstractFacade
{
    protected static function getFacadeAccessor(): string
    {
        return QueueService::class;
    }
}