<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models;

use ModulesGarden\OpenStackVpsCloud\Core\Models\ExtendedEloquentModel;

/**
 * Class Job
 * @property $job job id
 * @property $jobId
 * @property $id
 * @property $date -
 * @property $message -
 * @property $type
 * @property $additional - serialized
 */
class JobLog extends ExtendedEloquentModel
{
    /**
     * @var string
     */
    protected $table = 'JobLog';

    public function job()
    {
        return $this->hasOne(Job::class, 'job_id');
    }
}
