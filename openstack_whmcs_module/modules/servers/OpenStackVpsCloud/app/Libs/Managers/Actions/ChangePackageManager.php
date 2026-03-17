<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\Actions;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders\ChangePackageVmBuilder;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\BuildingVmContext;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\SettingVmDetailsContext;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\PostCreationSetters\PostChangePackageSetter;
use ModulesGarden\OpenStackVpsCloud\App\Helpers\Exceptions\PostponeTaskException;

class ChangePackageManager extends BaseManager implements BuildingVmContext, SettingVmDetailsContext
{
    /**
     * @var array
     */
    protected $varsForNextStage = [];

    /**
     * @return mixed|void
     * @throws Exception
     * @throws OSException
     */
    public function runBuildVM()
    {
        $vmBuilder = new ChangePackageVmBuilder($this->params);
        $vmBuilder->buildNetwork();
        $vmBuilder->buildFlavor();
        $vmBuilder->getVm()->setDetails();

        if ($vmBuilder->getVm()->getStatus() == VPSModel::STATUS_VERIFY_RESIZE) {
            throw new PostponeTaskException('Task postponed, awaiting resize verification');
        }

        $vmBuilder->buildVolumes();
        $vmBuilder->buildMetadata();


        $this->varsForNextStage = [
            'vm' => $vmBuilder->getVm(),
        ];
    }

    /**
     * @param VPSModel $vm
     * @param array $params
     * @return mixed|void
     * @throws \Exception
     */
    public function runSetVmDetailsAfterCreate(VPSModel $vm, array $params, array $interfacesToSet = [])
    {
        $setter = new PostChangePackageSetter($params, $vm);
        $setter->setSecurityGroups();
        $setter->setIPAddresses();
    }

    /**
     * @return array
     */
    public function getVarsForNextStage()
    {
        return $this->varsForNextStage;
    }

}