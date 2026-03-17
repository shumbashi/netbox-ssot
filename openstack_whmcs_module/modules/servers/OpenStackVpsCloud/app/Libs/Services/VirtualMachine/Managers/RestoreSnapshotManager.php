<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders\RestoreVolumeVmBuilder;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\CreationVMWrapper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\BuildingVmContext;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\CreateInstanceContext;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\SettingVmDetailsContext;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\TerminationVmContext;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\PostCreationSetters\PostRestoreSnapshotSetter;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\VmTerminateProcessor;

class RestoreSnapshotManager extends BaseVpsManager implements BuildingVmContext, CreateInstanceContext, SettingVmDetailsContext, TerminationVmContext
{
    /**
     * @var array
     */
    protected $varsForNextStage = [];

    /**
     * @var string|null
     */
    protected $newVolumeId;

    public function __construct(string $vmID, array $params = [], string $newVolumeID = null)
    {
        parent::__construct($vmID, $params);

        $this->newVolumeId = $newVolumeID;
    }

    /**
     * @return VPSModel
     * @throws OSException
     * @throws Exception
     * @throws \Exception
     */
    public function runBuildVM()
    {
        $vmBuilder = new RestoreVolumeVmBuilder($this->whmcsParams);
        $vmBuilder->buildName();
        $vmBuilder->buildKeyPair();
        $vmBuilder->buildFlavor();
        $vmBuilder->buildNetwork();
        $vmBuilder->buildMetadata();
        $vmBuilder->buildVolumes($this->newVolumeId);

        $this->varsForNextStage = [
            'vm' => $vmBuilder->getVm(),
        ];

        return $vmBuilder->getVm();
    }

    /**
     * @param VPSModel $vm
     * @return mixed|void
     * @throws Exception
     * @throws \Exception
     */
    public function runCreateInstance(VPSModel $vm)
    {
        $creationWrapper = new CreationVMWrapper($vm, true);
        $wrappedVm = $creationWrapper->getCreationVmApiModel();

        $result = Api::getInstance()->compute()->create(
            $wrappedVm->getName(),
            $wrappedVm->getFlavor(),
            $wrappedVm->getImage(),
            $wrappedVm->getNetworks(),
            $wrappedVm->getUserData(),
            $wrappedVm->getKeyPair(),
            $wrappedVm->getSecurityGroups(),
            $wrappedVm->getBlockDevices(),
            $wrappedVm->getPassword(),
            $wrappedVm->getMetadata(),
            $wrappedVm->getAvailabilityZone());

        $vm->setUUID($result['id']);
        $vm->setPassword($result['password']);

        $this->varsForNextStage = [
            'vm'              => $vm,
        ];
    }

    public function runSetVmDetailsAfterCreate(VPSModel $newVm, array $params)
    {
        // Custom field VM ID is changing in this step but old VM Id is necessary to remove old VM
        $this->varsForNextStage = [
            'oldVmId' => $params['customfields']['vmID'],
        ];

        $setter = new PostRestoreSnapshotSetter($params, $newVm);

        $setter->recreateNetwork();
        $setter->setMetadata();
        $setter->updateCustomFieldOfVmId();
        $setter->setPassword();
//        $setter->updateMetadata();
        $setter->sendRebuildEmailIfSet();
    }

    /**
     * Delete old VM
     * @param string|null $specificVmIdToDelete
     * @throws Exception
     * @throws OSException
     */
    public function runDeleteVm(string $specificVmIdToDelete = null)
    {
        $this->loadVM($specificVmIdToDelete);

        $terminator = new VmTerminateProcessor($this->vm, $this->whmcsParams['serviceid']);

        $terminator->deleteBackups();
        $terminator->deleteNetworking();

        /**
         * Delete SSH key from panel if key is not imported
         */
        if ($this->productConfig->getCafKeypair() && $this->vm->getSshKey())
        {
            $sshKeyName = $this->vm->getSshKey()->getName();
            if ($sshKeyName) {
                $terminator->deleteKeyFromPanel($sshKeyName);
            }
        }

        $terminator->deleteInstance();
        $terminator->deleteUsingBlockDevices();
        $terminator->deleteVmID();
    }

    /**
     * @return array
     */
    public function getVarsForNextStage()
    {
        return $this->varsForNextStage;
    }

}