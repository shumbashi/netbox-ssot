<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\Actions;


use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\TerminationVmContext;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\VmTerminateProcessor;

/**
 * Class TerminateAccManager
 * @package ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\Actions
 */
class TerminateAccManager extends BaseManager implements TerminationVmContext
{
    /**
     * @param string|null $specificVmIdToDelete
     * @throws Exception
     * @throws OSException
     */
    public function runDeleteVm(string $specificVmIdToDelete = null)
    {
        $vm = $this->loadVm();
        $terminator = new VmTerminateProcessor($vm, $this->params['serviceid']);
        if (empty($terminator->getVm()->getUUID()))
        {
            throw new \Exception('VmTerminateProcessor: Unable to load VM. UUID is empty.');
        }

        $terminator->deleteBackups();
        $terminator->deleteNetworking();
        $terminator->deleteInstance();
        $terminator->deleteUsingBlockDevices();
        $terminator->deleteVmID();
        $this->productCustomFields->updateFieldValue('vmID', '');
        $terminator->addDeleteVolumesTask();
        $terminator->deleteKeyPair();
        $terminator->deleteSecurityGroups();
        $terminator->deletePrivateFlavor();
        $terminator->deleteServiceSettingsFromDB();
    }

}