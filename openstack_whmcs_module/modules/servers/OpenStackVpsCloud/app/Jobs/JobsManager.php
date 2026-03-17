<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums\LoggerMessages;
use ModulesGarden\OpenStackVpsCloud\App\Helpers\Exceptions\PostponeTaskException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration as LegacyProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Facades\Logger;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Status;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Job as CoreJob;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Facades\Queue;

use \Carbon\Carbon;

class JobsManager extends CoreJob
{
    /**
     * @var int
     */
    protected $postponeTime = 5;

    /**
     * @var array
     */
    protected $params;

    protected $productConfig;
    protected ?array $productConfiguration = null;

    /**
     * @var array
     */
    protected $jobData;

    /**
     * @param array $params
     * @param int|null $pid
     * @return bool
     * @throws \Exception
     */
    public function runTask(array $params, ?int $pid)
    {
        if ($this->canRunTask($pid, $params['serviceid']) === false) {
            return false;
        }

        $this->params = $params;
        $this->productConfig = new LegacyProductConfiguration($params['serviceid']);
        $this->productConfiguration = (new ProductConfiguration($pid))->get();
        $this->jobData = (array)($this->model->data);

        $this->model->increaseRetryCount();

        try {
            $this->loadApiConnection();
            return $this->runTaskAction();
        } catch (PostponeTaskException $exception) {
            $this->log->info($exception->getMessage(), ['pid' => $this->params['pid'], 'sid' => $this->params['sid']]);
            if ($this->model->retry_count >= 100) {
                return false;
            }

        } catch (\Exception $exception) {
            Logger::critical(LoggerMessages::EXCEPTION, [
                'service' => $params['serviceid'],
                'message' => $exception->getMessage(),
                'stacktrace' => $exception->getTraceAsString()
            ]);

            $this->log->error($exception->getMessage(), ['pid' => $this->params['pid'], 'sid' => $this->params['sid']]);
            if ($this->model->retry_count >= 100) {
                return false;
            }

            return $this->postpone(true);
        } catch (\Throwable $throwable) {
            Logger::critical(LoggerMessages::EXCEPTION, [
                'service' => $params['serviceid'],
                'message' => $throwable->getMessage(),
                'stacktrace' => $throwable->getTraceAsString()
            ]);

            $this->log->error($throwable->getMessage(), ['pid' => $this->params['pid'], 'sid' => $this->params['sid']]);
            if ($this->model->retry_count >= 100) {
                return false;
            }

            return $this->postpone(true);
        }
    }


    protected function loadApiConnection()
    {
        if (!$this->productConfig->getTenantID()) {
            throw new \Exception('Tenant ID for server ' . $this->params['serverid'] . ' is empty');
        }

        $tenant = Factory::getTenantAsUser($this->params, $this->productConfig->getTenantID());
        $tenant->connect();
    }

    /**
     * @param bool $isError
     * @return bool
     */
    public function postpone($isError = false)
    {
        if ($isError === true) {
            $this->model->setError();
        } else {
            $this->model->setWaiting();
        }

        $this->model->setRetryAfter(Carbon::now()->addSeconds($this->postponeTime)->format('Y-m-d H:i:s'));

        return false;
    }

    /**
     * @param int $postponeTime
     */
    public function setPostponeTime(int $postponeTime)
    {
        $this->postponeTime = $postponeTime;
    }

    /**
     * @param $classOfJob
     * @param array $arguments
     * @return Models\Job
     */
    protected function addTask($classOfJob, array $arguments = [])
    {
        return Queue::push($classOfJob,
            $arguments,
            'default',
            null,
            'Hosting',
            $this->params['serviceid']);
    }

    /**
     * @param array $dataToAdd
     */
    protected function addDataToJob(array $dataToAdd = [])
    {
        $this->jobData['data'] = $dataToAdd;
        $this->model->data = $this->jobData;
        $this->model->save();
    }

    /**
     * @param array $dataToAdd
     */
    protected function addDataToClearProcess(array $dataToAdd = [])
    {
        $this->jobData['clearStageData'] = $dataToAdd;
        $this->model->data = $this->jobData;
        $this->model->save();
    }

    public function isParentJobStatusWaiting($parentId)
    {
        if (is_null($parentId)) {
            throw new \Exception('This job cannot be run manually');
        }

        return JobManager::getStatus($parentId) == Status::WAITING;
    }

    private function canRunTask(int $packageId, $serviceId = null)
    {
        if (!$serviceId) {
            $this->model->setError();
            $this->log->error('Service ID is empty.', ['pid' => $packageId]);
            return false;
        }

        if (!Service::find($serviceId)) {
            $this->model->setError();
            $this->log->error('Hosting ID #' . $serviceId . ' does not exist.', ['pid' => $packageId, 'sid' => $serviceId]);
            return false;
        }
    }
}