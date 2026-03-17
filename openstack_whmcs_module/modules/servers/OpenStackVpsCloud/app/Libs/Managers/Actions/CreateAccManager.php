<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\Actions;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders\CreateAccVmBuilder;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\CreationVMWrapper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\BuildingVmContext;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\CreateInstanceContext;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\SettingVmDetailsContext;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\PostCreationSetters\PostCreateAccountSetter;

class CreateAccManager extends BaseManager implements BuildingVmContext, CreateInstanceContext, SettingVmDetailsContext
{
    /**
     * @var array
     */
    protected $varsForNextStage = [];

    /**
     * @return mixed|VPSModel
     * @throws Exception
     * @throws OSException
     * @throws \Exception
     */
    public function runBuildVM()
    {
        $vmBuilder = new CreateAccVmBuilder($this->params);
        $vmBuilder->buildCustomScript();
        $vmBuilder->buildMetadata();
        $vmBuilder->buildFlavor();
        $vmBuilder->buildKeyPair();
        $vmBuilder->buildName();
        $vmBuilder->buildNetwork();
        $vmBuilder->buildImage();
        $vmBuilder->buildVolumes();
        $vmBuilder->buildPassword();
        $vmBuilder->buildAvailabilityZone();

        $this->varsForNextStage = [
            'vm' => $vmBuilder->getVm(),
        ];
    }

    /**
     * @param VPSModel $vm
     * @return mixed|string
     * @throws \Exception
     */
    public function runCreateInstance(VPSModel $vm)
    {
        $creationWrapper              = new CreationVMWrapper($vm);
        $wrappedVm                    = $creationWrapper->getCreationVmApiModel();

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

        $this->varsForNextStage['vm'] = $vm;
    }

    public function runSetVmDetailsAfterCreate(VPSModel $vm, array $params)
    {
        $setter = new PostCreateAccountSetter($params, $vm);
//        $setter->updateMetadata();
        $setter->updateCustomFieldOfVmId();
        $setter->setPassword();
        $setter->recreateNetwork();
        $setter->setUsernameFromCustomScript();
        $setter->setSshKeyPair();
        $setter->setIPAddresses();
        $setter->setSecurityGroups();
        $setter->setProtectVM();

        if (!$params['emailSent']) {
            $setter->sendCreationEmailIfSet();
        }

        $this->varsForNextStage['emailSent'] = true;
        $this->varsForNextStage['vm'] = $vm;
    }

    public function getVarsForNextStage()
    {
        return $this->varsForNextStage;
    }
}