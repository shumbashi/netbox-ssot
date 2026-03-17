<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\PostCreationSetters;


use ModulesGarden\OpenStackVpsCloud\App\Jobs\CreateInterfaces;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\FirewallManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\SecurityGroupModel;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Facades\Queue;

class PostRestoreSnapshotSetter extends BaseSetter
{
    /**
     * @var VPSModel
     */
    protected $oldVm;

    /**
     * PostRestoreSnapshotSetter constructor.
     * @param array $params
     * @param VPSModel $vm
     * @throws OSException
     * @throws \OSException
     */
    public function __construct(array $params, VPSModel $vm)
    {
        parent::__construct($params, $vm);

        $this->oldVm             = $this->tenant->VPS($params['customfields']['vmID']);
    }

    /**
     * @param array $interfacesToSet
     * @throws Exception
     * @throws \Exception
     */
    public function recreateNetwork()
    {
        $securityGroupToTransfer = $this->getSecurityGroupsToTransfer();

        $migratingInterfaces = [];
        $interfaces = Api::getInstance()->compute()->listInterfaces($this->oldVm->getUUID());
        foreach ($interfaces['interfaceAttachments'] as $interface) {

            $floatingIps = Api::getInstance()->network()->getFloatingIps([
                'port_id' => $interface['port_id'],
            ]);

            $migratingInterfaces[] = ['interface' => $interface, 'floating_ips' => $floatingIps];
            Api::getInstance()->compute()->deleteInterface($this->oldVm->getUUID(), $interface['port_id']);
        }

        /*Interfaces used for vm creation, detach before attaching new*/
        $portsToDetach = [];
        $newVmInterfaces = Api::getInstance()->compute()->listInterfaces($this->vm->getUUID());
        foreach ($newVmInterfaces['interfaceAttachments'] as $interface) {
            $portsToDetach[] = $interface['port_id'];
        }

        Queue::push(CreateInterfaces::class,
            [
                'hid'                         => $this->params['serviceid'],
                'pid'                         => $this->params['pid'],
                'migratingInterfaces'         => $migratingInterfaces,
                'portsToDetach'               => $portsToDetach,
                'newVmID'                     => $this->vm->getUUID(),
                'securityGroupsToTransfer'    => $securityGroupToTransfer,
            ],
            'default',
            null,
            'Hosting',
            $this->params['serviceid']);

    }

    /**
     * @return array 'group ID => group name'
     * @throws \Exception
     */
    public function getSecurityGroupsToTransfer(): array
    {
        $oldVmId        = $this->params['customfields']['vmID'];
        $securityGroups = Api::getInstance()->compute()->getSecurityGroupList($oldVmId)['security_groups'];

        $securityGroupsToTransfer = [];

        foreach ($securityGroups as $group)
        {
            if (!$securityGroupsToTransfer[$group['id']])
            {
                $securityGroupsToTransfer[$group['id']] = $group['name'];
            }
        }

        return $securityGroupsToTransfer;
    }

    /**
     * @param array $portGroups
     * @return array
     * @throws \Exception
     */
    private function sortInterfaces(array $portGroups): array
    {
        $sortedIPs = array_column(
            Api::getInstance()->compute()->getVPSDetails($this->oldVm->getUUID())['addresses'],
            'addr'
        );

        $sortedPortsInterfaces = [];

        foreach ($sortedIPs as $ip)
        {
            foreach ($portGroups as $portID => $interfaces)
            {
                if (reset($interfaces)->getFixedIP() == $ip)
                {
                    $sortedPortsInterfaces[$portID] = $interfaces;
                    break;
                }
            }
        }

        return $sortedPortsInterfaces;

    }

    /**
     * Switch Security Groups To New VM
     */
    public function setSecurityGroups()
    {
        $firewallManager   = new FirewallManager($this->params);
        $oldSecurityGroups = $firewallManager->getGroupList();

        foreach ($oldSecurityGroups as $group)
        {
            if ($group['name'] == SecurityGroupModel::NAME_DEFAULT)
            {
                continue;
            }

            try
            {
                Api::getInstance()->compute()->assignSecurityGroupVPS($this->vm->UUID, $group['name']);
            }
            catch (\Exception $exception)
            {
                //nothing to do, group is already assigned for this VM.
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function setMetadata()
    {
        $metadata = Api::getInstance()->compute()->getMetadata($this->oldVm->getUUID())['metadata'];

        if (!empty($metadata) && is_array($metadata))
        {
            $this->vm->checkStateTask();
            Api::getInstance()->compute()->updateMetadata($this->vm->UUID, $metadata);
        }
    }


}