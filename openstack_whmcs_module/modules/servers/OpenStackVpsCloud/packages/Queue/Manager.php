<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue;

use Carbon\Carbon;
use ModulesGarden\OpenStackVpsCloud\Core\DependencyInjection\DependencyInjection;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Status;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Exceptions\RunTaskException;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Services\Log;

class Manager
{
    /**
     * @var Models\Job
     */
    protected $job;

    /**
     * @var Log
     */
    protected $log;

    public function __construct(Models\Job $job)
    {
        $this->job = $job;

        $this->log = new Log($this->job);
    }

    public function fire()
    {
        try
        {
            $preRunJobStatus = $this->job->status;
            $this->job->setRunning();

            [$instance, $method] = $this->getJobInstance($this->job->job);

            $ret = $this->fireJob($instance, $method, (array)$this->job->data);

            if ($ret !== false)
            {
                $this->job->setFinished();
            }
        }
        catch (\Throwable $ex)
        {
            //Reset retry count if job is changing status to error
            $this->resetRetryCountIfJobNotError($preRunJobStatus);

            //Set error in job
            $this->job->setError();
            $this->job->setRetryAfter(Carbon::now()->addSeconds(60)->format('Y-m-d H:i:s'));
            $this->job->increaseRetryCount();

            //add log message
            $this->log->error($ex->getMessage(), debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
            throw new RunTaskException($ex->getMessage());
        }
        finally
        {
            $this->checkRetryCounterOverflow($this->job, $instance);
        }
    }

    protected function resetRetryCountIfJobNotError($preRunJobStatus)
    {
        if ($preRunJobStatus != Status::ERROR && $preRunJobStatus != Status::FAILED )
        {
            $this->job->retry_count = 0;
            $this->job->save();
        }
    }

    protected function getJobInstance(string $jobClass):array
    {
        [$class, $method] = $this->parseJob($jobClass);

        $instance = $this->resolve($class);

        if (method_exists($instance, 'setJobModel'))
        {
            $instance->setJobModel($this->job);
        }

        return [$instance, $method];
    }

    protected function fireJob(Job $instance, string $method, array $data)
    {
        return call_user_func_array([$instance, $method], array_values($data));
    }

    /**
     * Parse the job declaration into class and method.
     *
     * @param string $job
     * @return array
     */
    protected function parseJob($job)
    {
        $segments = explode('@', $job);

        return count($segments) > 1 ? $segments : [$segments[0], 'handle'];
    }

    /**
     * Resolve the given job handler.
     *
     * @param string $class
     * @return mixed
     */
    protected function resolve($class)
    {
        return DependencyInjection::create($class);
    }

    protected function checkRetryCounterOverflow(Models\Job $job, $instance):void
    {
        if ($job->status == Status::FINISHED)
        {
            return;
        }

        $maxRetryCount = $instance instanceof LimitedRetryCountInterface ?
            $instance->maxRetryCount() :
            Job::DEFAULT_MAX_RETRY_COUNT;

        if ($maxRetryCount < $job->retry_count)
        {
            $job->setStatus(Status::FAILED);
        }
    }
}
