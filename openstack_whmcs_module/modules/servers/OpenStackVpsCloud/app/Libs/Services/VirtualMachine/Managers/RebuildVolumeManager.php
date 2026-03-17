<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders\RebuildVolumeVmBuilder;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\CreationVMWrapper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\BuildingVmContext;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\CreateInstanceContext;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\SettingVmDetailsContext;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\TerminationVmContext;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\PostCreationSetters\PostRebuildVmSetter;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\VmTerminateProcessor;

class RebuildVolumeManager extends BaseVpsManager implements BuildingVmContext, CreateInstanceContext, SettingVmDetailsContext, TerminationVmContext
{
    /**
     * @var array
     */
    protected $varsForNextStage = [];

    protected $additionalVars = [];

    public function __construct(string $vmID, array $params = [], array $additionalVars = [])
    {
        parent::__construct($vmID, $params);

        $this->additionalVars = $additionalVars;
    }

    /**
     * @return VPSModel|void
     * @throws OSException
     * @throws \OSException
     * @throws Exception
     * @throws \Exception
     */
    public function runBuildVm()
    {
        $vmBuilder = new RebuildVolumeVmBuilder($this->whmcsParams, $this->additionalVars);
        $vmBuilder->buildCustomScript();
        $vmBuilder->buildMetadata();
        $vmBuilder->buildFlavor();
        $vmBuilder->buildKeyPair();
        $vmBuilder->buildName();
        $vmBuilder->buildImage();
        $vmBuilder->buildVolumes();
        $vmBuilder->buildPassword();
        $vmBuilder->buildNetwork();

        $this->varsForNextStage = [
            'vm' => $vmBuilder->getVm()
        ];

        return $vmBuilder->getVm();
    }

    /**
     * @param VPSModel $vm
     * @return mixed|void
     * @throws Exception
     */
    public function runCreateInstance(?VPSModel $vm)
    {
        $creationWrapper = new CreationVMWrapper($vm);
        $wrappedVm       = $creationWrapper->getCreationVmApiModel();

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
            'vm' => $vm
        ];
    }

    /**
     * @param VPSModel $newVm
     * @param array $params
     * @param array $interfacesToSet
     * @return mixed|void
     * @throws Exception
     * @throws \Exception
     */
    public function runSetVmDetailsAfterCreate(VPSModel $newVm, array $params)
    {
        // Custom field VM ID is changing in this step but old VM Id is necessary to remove old VM
        $this->varsForNextStage = [
            'oldVmId' => $params['customfields']['vmID'],
        ];

        $setter = new PostRebuildVmSetter($params, $newVm);
        $setter->setPassword();
        $setter->setUsernameFromCustomScript();
//        $setter->updateMetadata();
        $setter->setProtectVM();
        $setter->setSshKeyPair();
        $setter->setIPAddresses();
        $setter->updateCustomFieldOfVmId();
    }

    /**
     * Delete old VM
     *
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
        $terminator->deleteInstance();
        $terminator->deleteUsingBlockDevices();
        $terminator->deleteVmID();
        $terminator->addDeleteVolumesTask();

        /**
         * Delete SSH key from panel if key is not imported
         */
        if ($this->productConfig->getCafKeypair() && $this->vm->getSshKey())
        {
            $sshKeyName = $this->vm->getSshKey()->getName();
            if (!empty($sshKeyName))
            {
                $terminator->deleteKeyFromPanel($sshKeyName);
            }
        }
    }

    /**
     * @return array
     */
    public function getVarsForNextStage()
    {
        return $this->varsForNextStage;
    }
}