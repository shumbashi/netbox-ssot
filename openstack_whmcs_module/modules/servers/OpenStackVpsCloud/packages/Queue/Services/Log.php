<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Services;

use Exception;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\LogStatus;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\Job;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\JobLog;

class Log
{
    /**
     * @var Models\Job
     */
    protected $job;

    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    /**
     * @param $type
     * @param $message
     * @param null $additional
     * @return $this
     */
    protected function log($type, $message, $additional = null)
    {
        try
        {
            $model             = new JobLog();
            $model->job_id     = $this->job->id;
            $model->type       = $type;
            $model->message    = $message;
            $model->additional = serialize($additional);
            $model->save();
        }
        catch (Exception $ex)
        {
            var_dump($ex->getMessage());
        }

        return $this;
    }

    /**
     * @param $message
     * @param null $additional
     * @return $this
     */
    public function error($message, $additional = null)
    {
        $this->log(LogStatus::ERROR, $message, $additional);

        return $this;
    }

    /**
     * @param $message
     * @param null $additional
     * @return $this
     */
    public function info($message, $additional = null)
    {
        $this->log(LogStatus::INFO, $message, $additional);

        return $this;
    }

    /**
     * @param $message
     * @param null $additional
     * @return $this
     */
    public function success($message, $additional = null)
    {
        $this->log(LogStatus::SUCCESS, $message, $additional);

        return $this;
    }
}
