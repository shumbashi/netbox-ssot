<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class Job
 */
class Job implements ShouldQueue, LimitedRetryCountInterface
{
    const DEFAULT_MAX_RETRY_COUNT = 100;
    /**
     * @var Services\Log
     */
    protected $log;

    /**
     * @var Models\Job
     */
    protected $model;

    public function handle()
    {
        $this->log->info('Override me please!');
    }

    /**
     * @param Models\Job $job
     */
    public function setJobModel(Models\Job $job)
    {
        $this->model = $job;

        $this->log = new Services\Log($this->model);
    }

    public function maxRetryCount():int
    {
        return self::DEFAULT_MAX_RETRY_COUNT;
    }

    public function delay($seconds = 60)
    {
        $this->model->setRetryAfter(Carbon::now()->addSeconds($seconds)->format('Y-m-d H:i:s'));
    }
}
