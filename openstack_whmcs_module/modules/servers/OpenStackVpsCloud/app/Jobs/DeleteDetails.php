<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use WHMCS\Service\Service;

class DeleteDetails extends JobsManager
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @param int $hid
     * @param int|null $pid
     * @param array $data
     * @return bool|void
     * @throws \Exception
     */
    public function handle(int $hid = 0, int $pid = null, array $data = [])
    {
        $this->params = WhmcsParamsHelper::getWhmcsParamsByHostingId($hid);

        return $this->runTask($this->params, $pid);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function runTaskAction()
    {
        if(!isset($this->params['customfields']['vmID']) ||
           (isset($this->params['customfields']['vmID']) && empty($this->params['customfields']['vmID'])))
        {
            $service = Service::findOrFail($this->params['serviceid']);
            $service->username    = '';
            $service->password    = '';
            $service->dedicatedIp = '';
            $service->assignedIps = '';
            $service->save();
            return true;
        }

        return $this->postpone();
    }


}