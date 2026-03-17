<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\Traits\TaskErrorTrait;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;

class DeleteNetworking extends JobsManager
{
    use TaskErrorTrait;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var string
     */
    protected $vmID;

    /**
     * @param int $hid
     * @param int|null $pid
     * @param string|null $vmID
     * @return bool|void
     * @throws \Exception
     */

    public function handle(int $hid = 0, int $pid = null, string $vmID = null)
    {
        $this->params = WhmcsParamsHelper::getWhmcsParamsByHostingId($hid);
        $this->vmID = $vmID;
        $this->setPostponeTime(2);

        return $this->runTask($this->params, $pid);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function runTaskAction()
    {
        $ports = $this->getInstancePorts();
        foreach ($ports as $port) {
            try {
                $this->deleteFloatingIps($port['id']);
                Api::getInstance()->network()->deletePort($port['id']);

            } catch (\Exception $e) {
                $this->errors[] = $e->getMessage();
            }
        }

        if (!empty($this->errors)) {
            throw new \Exception($this->getErrorMessage());
        }

        return true;
    }

    protected function getInstancePorts()
    {
        return Api::getInstance()->network()->listPorts([
            'device_id' => $this->vmID,
            'device_owner' => 'compute:nova'
        ]);
    }

    protected function deleteFloatingIps(string $portId)
    {
        $floatingIps = Api::getInstance()->network()->getFloatingIps([
            'port_id' => $portId,
        ]);

        foreach ($floatingIps as $floatingIp) {
            Api::getInstance()->network()->deleteFloatingIP($floatingIp['id']);
        }
    }
}