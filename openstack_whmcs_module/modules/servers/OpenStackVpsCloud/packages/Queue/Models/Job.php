<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models;

use ModulesGarden\OpenStackVpsCloud\Core\Models\ExtendedEloquentModel;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Status;

/**
 * Class Job
 * @property string $job
 * @property string $data
 * @property string $queue
 * @property string $status
 * @property int $parent_id
 * @property int $rel_id
 * @property int $rel_type
 * @property string $rel_custom
 * @property $retry_after
 * @property int $retry_count
 *
 * @todo - obsługa dzieci
 */
class Job extends ExtendedEloquentModel
{
    protected $casts = ['data' => 'array'];
    /**
     * @var string
     */
    protected $table = 'Job';

    /**
     * @return $this
     */
    public function increaseRetryCount()
    {
        $this->retry_count++;
        $this->save();

        return $this;
    }

    /**
     * @return JobLog::class[]
     */
    public function logs()
    {
        return $this->hasMany(JobLog::class, 'job_id');
    }

    /**
     * @return $this
     */
    public function setError()
    {
        $this->setStatus(Status::ERROR);

        return $this;
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        $this->save();

        return $this;
    }

    /**
     * @return $this
     */
    public function setFinished()
    {
        $this->setStatus(Status::FINISHED);

        return $this;
    }

    /**
     * @return bool
     */
    public function isRetryAfterSet()
    {
        return !empty($this->retry_after) && $this->retry_after != "0000-00-00 00:00:00";
    }

    /**
     * @param $time
     * @return $this
     */
    public function setRetryAfter($time)
    {
        $this->retry_after = $time;
        $this->save();

        return $this;
    }

    /**
     * @return $this
     */
    public function setRunning()
    {
        $this->setStatus(Status::RUNNING);

        return $this;
    }

    /**
     * @return $this
     */
    public function setWaiting()
    {
        $this->setStatus(Status::WAITING);

        return $this;
    }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function scopeRelatedItem($query, $itemType, $itemId, $itemCustom)
    {
        return $query->where('rel_type', $itemType)->where('rel_id', $itemId)->where('rel_custom', $itemCustom);
    }
}
