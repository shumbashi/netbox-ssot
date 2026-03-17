<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue;

use Illuminate\Contracts\Queue\Queue;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Status;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\Job as JobModel;
use Carbon\Carbon;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Services\PriorityJobs;

class DatabaseQueue implements Queue
{
    public function bulk($jobs, $data = '', $queue = null)
    {
    }

    public function getConnectionName()
    {
        return '';
    }

    public function later($delay, $job, $data = '', $queue = null)
    {
    }

    public function laterOn($queue, $delay, $job, $data = '')
    {
    }

    /**
     * @param string $queue
     * @return mixed
     */
    public function pop($queue = 'default')
    {
        $table   = (new JobModel())->getTable();
        $rawOrderBy = (new PriorityJobs())->getPreparedSqlQueue();

        $result =  JobModel::select("{$table}.*")
            ->leftJoin($table.' as parent', function($join) use($table) {
                $join->on($table.'.parent_id', '=', 'parent.id');
            })
            ->where($table.'.queue', $queue)
            ->where(function($query) use ($table) {
                $query->where($table.'.status', '=', '')
                    ->orWhere(function($query) use ($table) {
                        $query->where($table.'.status', '=', Status::PENDING);
                        $query->where($table.'.retry_after', '<', Carbon::now());
                    })
                    ->orWhere(function($query) use ($table) {
                        $query->whereIn($table.'.status', [Status::ERROR, Status::WAITING]);
                        $query->where($table.'.retry_after', '<', Carbon::now());
                        $query->where($table.'.retry_count', '<', Job::DEFAULT_MAX_RETRY_COUNT);
                    });
            })
            ->where(function($query) use ($table){
                $query->whereRaw("{$table}.parent_id IS NULL");
                $query->orWhere("parent.status", Status::FINISHED);
            });

        if (!empty($rawOrderBy))
        {
            $result->orderByRaw("FIELD({$table}.job, {$rawOrderBy}) DESC");
        }

        return $result->orderBy("{$table}.updated_at", 'ASC')
            ->orderBy("{$table}.id", 'ASC')
            ->first();
    }

    /**
     * @param $job
     * @param string $data
     * @param string $queue
     * @param int $parentId
     * @param int $relType
     * @param int $relId
     * @param int $relCustom
     */
    public function push($job, $data = '', $queue = 'default', $parentId = null, $relType = null, $relId = null, $relCustom = null)
    {
        $model             = new JobModel();
        $model->job        = $job;
        $model->data       = $data;
        $model->queue      = $queue;
        $model->parent_id  = $parentId;
        $model->rel_id     = $relId;
        $model->rel_type   = $relType;
        $model->rel_custom = $relCustom;
        $model->status     = Status::PENDING;
        $model->created_at = Carbon::now()->toDateTimeString();
        $model->updated_at = Carbon::now()->toDateTimeString();
        $model->save();

        return $model;
    }

    public function pushOn($queue, $job, $data = '')
    {
    }

    public function pushRaw($payload, $queue = null, array $options = [])
    {
    }

    public function setConnectionName($name)
    {
    }

    public function size($queue = null)
    {
        return 0;
    }
}
