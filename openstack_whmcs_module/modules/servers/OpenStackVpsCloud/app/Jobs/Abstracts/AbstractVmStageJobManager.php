<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs\Abstracts;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\JobsManager;
use ModulesGarden\OpenStackVpsCloud\App\Models\Job;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\JobNameTranslator;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Status;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Services\Log;

abstract class AbstractVmStageJobManager extends JobsManager
{

    /**
     * @var array
     */
    protected $params;

    /**
     * @var array
     */
    protected $data;

    /**
     * @param string|null $stage
     * @param array $data
     * @return mixed
     */
    abstract function runStageProcess(string $stage = null , array $data = []);

    abstract function runClearProcess(string $job, array $data = []);

    abstract function getFirstAction();

    /**
     * @param int $hid
     * @param int $pid
     * @param array $data
     * @return bool|void
     * @throws \Exception
     */
    public function handle(int $hid = 0, int $pid = null, array $data = [])
    {
        $this->params                     = WhmcsParamsHelper::getWhmcsParamsByHostingId($hid);
        $this->data             = $data;
        $this->data['parentId'] = $this->model->id;

        return $this->runTask($this->params, $pid);
    }

    /**
     * @return mixed
     */
    public function runTaskAction()
    {
        $nextStage = $this->jobData['data']['stage'];
        $currentStageJobId = $this->jobData['data']['jobID'];

        $data = $this->data;

        if ($currentStageJob = Job::find($currentStageJobId)) {
            $data = array_merge($data, Arr::get($currentStageJob->data, 'data', []));

            if ($currentStageJob->status == Status::ERROR) {
                $jobName = (new JobNameTranslator())->format($currentStageJob->job);

                $log = new Log($this->model);
                $log->error(sprintf('Task failed at stage %s', $jobName));

                try {
                    $this->runClearProcess($currentStageJob->job, $currentStageJob->data);
                }
                catch (\Throwable $t) {
                    $log->error(sprintf('Task clean up failed: %s', $t->getMessage()));
                }

                $currentStageJob->status = Status::FAILED;
                $currentStageJob->save();

                $this->model->status = Status::FAILED;
                $this->model->save();

                return false;
            }

            if ($currentStageJob->status != Status::FINISHED) {
                return $this->postpone();
            }
        }

        return $this->runStageProcess($nextStage ?: $this->getFirstAction(), $data);
    }


    /**
     * @param string $actionType
     * @param array $data
     * @return array
     */
    protected function parseArguments(string $actionType, array $data = [])
    {
        return [
            'hid'            => (int)$this->params['serviceid'],
            'pid'            => (int)$this->params['pid'],
            'actionType'     => $actionType,
            'data'           => $data
        ];
    }
}