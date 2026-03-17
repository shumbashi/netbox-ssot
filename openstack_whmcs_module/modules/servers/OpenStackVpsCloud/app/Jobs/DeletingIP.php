<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\NetworkInterfacesManager;

use Illuminate\Database\Capsule\Manager as DB;

class DeletingIP extends JobsManager
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var array
     */
    protected array $data = [];

    public function handle(int $hid = 0, int $pid = null, array $data = [])
    {
        $this->params    = WhmcsParamsHelper::getWhmcsParamsByHostingId($hid);
        $this->data    = $data;

        return $this->runTask($this->params, $pid);
    }

    public function runTaskAction()
    {
        try {
            $interfacesManager = new NetworkInterfacesManager($this->params['customfields']['vmID'], $this->params);
            $interfacesManager->deleteIpFromPort(
                $this->data['port_id'],
                $this->data['fixed_ip_address'],
                true,
            );

            $ips = $interfacesManager->getServiceIps($this->params['model']->dedicatedip);

            /*This must use raw query model due to fillables*/
            DB::statement("UPDATE tblhosting SET dedicatedip = ?, assignedips = ? WHERE id = ?", [
                $ips['dedicatedip'],
                $ips['assignedips'],
                $this->params['serviceid']]);
        }
        catch (\Exception $exception)
        {
            return $exception->getMessage();
        }
    }
}