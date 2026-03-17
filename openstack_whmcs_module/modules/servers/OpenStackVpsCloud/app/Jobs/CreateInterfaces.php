<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums\LoggerMessages;
use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\Traits\TaskErrorTrait;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OpenStackApiException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\NetworkInterfacesManager;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Facades\Logger;
use Illuminate\Database\Capsule\Manager as DB;

class CreateInterfaces extends JobsManager
{
    use TaskErrorTrait;

    protected array $migratingInterfaces = [];

    protected array $portsToDetach = [];

    protected string $newVmId = '';
    protected array $securityGroupsToTransfer = [];

    public function handle(int $hid = 0, int $pid = null, array $migratingInterfaces = [], array $portsToDetach = [], string $newVmID = null, array $securityGroupsToTransfer = [])
    {
        $this->params = WhmcsParamsHelper::getWhmcsParamsByHostingId($hid);
        $this->migratingInterfaces = $migratingInterfaces;
        $this->portsToDetach = $portsToDetach;
        $this->newVmId = $newVmID;
        $this->securityGroupsToTransfer = $securityGroupsToTransfer;

        return $this->runTask($this->params, $pid);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function runTaskAction()
    {
        if (!$this->portsDetached()) {
            return $this->postpone();
        }

        foreach ($this->migratingInterfaces as $interface) {
            /*
             * Note: Ports created by specifying 'net_id' during instance creation
             * are managed by Neutron and bound to the instance lifecycle.
             * As a result, these ports will be automatically deleted when the instance is terminated.
             */
            $port = $this->getPort($interface['interface']['port_id']);
            if (!$port) {
                /*Attempt to recreate*/
                $port = $this->recreatePort($interface['interface'], $interface['floating_ips']);
            }

            if (!$port) {
                continue;
            }

            /*Apply security group directly to ports*/
            Api::getInstance()->network()->updatePort($port['id'], [
                'security_groups' => array_keys($this->securityGroupsToTransfer)
            ]);

            Api::getInstance()->compute()->createInterface($this->newVmId, $port['id']);
        }

        /* Remove creation ip addresses from a new VM*/
        foreach ($this->portsToDetach as $portId) {
            Api::getInstance()->compute()->deleteInterface($this->newVmId, $portId);
            try {
                Api::getInstance()->network()->deletePort($portId);
            } catch (\Exception $exception) { }
        }

        /* Assign group from old VM to new VM */
        foreach ($this->securityGroupsToTransfer as $groupName) {
            try {
                Api::getInstance()->compute()->assignSecurityGroupVPS($this->newVmId, $groupName);
            } catch (\Exception $exception) {
                Logger::critical(LoggerMessages::EXCEPTION, [
                    'service' => $this->params['serviceid'],
                    'message' => $exception->getMessage(),
                    'stacktrace' => $exception->getTraceAsString()
                ]);
            }
        }

        $interfacesManager = new NetworkInterfacesManager($this->newVmId, $this->params);
        $ips = $interfacesManager->getServiceIps($this->params['model']->dedicatedip);

        /*This must use raw query due to fillables*/
        DB::statement("UPDATE tblhosting SET dedicatedip = ?, assignedips = ? WHERE id = ?", [
            $ips['dedicatedip'],
            $ips['assignedips'],
            $this->params['serviceid']]);

        return true;
    }

    private function recreatePort(array $interface, array $floatingIps)
    {
        try {
            $recreatedPort = Api::getInstance()->network()->createPort([
                'network_id' => $interface['net_id'],
                'fixed_ips' => $interface['fixed_ips'],
                'mac_address' => $interface['mac_address'],
            ]);

            foreach ($floatingIps as $floatingIp) {
                $this->migrateFloatingIp($recreatedPort['id'], $floatingIp['fixed_ip_address'], $floatingIp['id']);
            }

            return $recreatedPort;
        } catch (OpenStackApiException $exc) {
            if ($exc->getCode() == 409) {
                return null; // If IP address is already allocated
            }

            throw $exc;
        }
    }

    private function migrateFloatingIp(string $portId, string $fixedIp, string $floatingId): void
    {
        $existingFloatingIp = Api::getInstance()->network()->getFloatingIp($floatingId);

        /*Check if detached from previous port*/
        if (empty($existingFloatingIp['port_id']) &&
            empty($existingFloatingIp['fixed_ip_address'])) {
            Api::getInstance()->network()->updateFloatingIP($floatingId, [
                'port_id' => $portId,
                'fixed_ip_address' => $fixedIp,
            ]);
        }

        //TODO: do something here
    }

    protected function getPort(string $portId)
    {
        try {
            return Api::getInstance()->network()->getPort($portId);
        } catch (OpenStackApiException $exception) {
            if ($exception->getCode() == 404) {
                return null;
            }

            throw $exception;
        }
    }

    private function portsDetached(): bool
    {
        foreach ($this->migratingInterfaces as $interface) {
            $port = $this->getPort($interface['interface']['port_id']);
            if (!$port) {
                continue;
            }

            if (empty($port['device_id'])) {
                continue;
            }

            return false;
        }

        return true;
    }

}