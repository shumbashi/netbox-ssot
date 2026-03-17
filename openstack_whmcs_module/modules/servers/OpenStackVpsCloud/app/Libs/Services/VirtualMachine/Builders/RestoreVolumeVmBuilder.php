<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders;


use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\BlockDeviceModel;

class RestoreVolumeVmBuilder extends BaseVmBuilder
{
    /**
     * @var VPSModel
     */
    protected $newVm;

    /**
     * RestoreVolumeVmBuilder constructor.
     * @param array $params
     * @throws OSException
     * @throws \OSException
     */
    public function __construct(array $params)
    {
        parent::__construct($params);

        $this->newVm = $this->tenant->VPS();
    }

    /**
     * Build VM name
     */
    public function buildName()
    {
        $this->newVm->setName($this->vm->getName());
    }

    /**
     * Build VM key pair
     */
    public function buildKeyPair()
    {
        $this->newVm->setSshKey($this->vm->getSshKey());
    }

    /**
     * Build VM flavor
     *
     * @throws OSException
     * @throws Exception
     */
    public function buildFlavor()
    {
        $this->newVm->setFlavor($this->vm->getFlavor());
    }

    /**
     * Build VM network
     *
     * @throws \Exception
     */
    public function buildNetwork()
    {
        $port = Api::getInstance()->network()->createPort([
            'network_id' => $this->productConfig['fixed_network']
        ]);

        $this->newVm->setCreationPortsIDs([
            $port['id']
        ]);
    }

    /**
     * @param string|null $newVolumeId
     * @return null
     */
    public function buildVolumes(string $newVolumeId = null)
    {
        /**
         * @var BlockDeviceModel $newVolume
         */
        $newVolume = $this->tenant->blockDevice();
        $newVolume->setUUID($newVolumeId);

        $this->newVm->addBlockDevice($newVolume);

    }

    /**
     * @return VPSModel
     */
    public function getVm()
    {
        return $this->newVm;
    }


}