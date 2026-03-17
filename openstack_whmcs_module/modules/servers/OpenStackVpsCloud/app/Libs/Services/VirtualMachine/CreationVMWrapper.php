<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine;


use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Decorators\BlockDeviceDecorator;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\Creation\CreationBlockDevice;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\Creation\CreationNetworks;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\Creation\CreationSecurityGroups;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\Creation\CreationVmApiModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\FlavorModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\ImageModel;

class CreationVMWrapper
{
    /**
     * @var VPSModel
     */
    protected $vm;

    protected ?array $wrappedNetwork = [];

    /**
     * @var array
     */
    protected $wrappedBlockDevices = [];

    /**
     * @var array
     */
    protected $wrappedSecurityGroups;

    /**
     * @var array
     */
    protected $wrappedMetadata;

    protected $wrappedUserData;
    protected $wrappedKeyPairs;

    /**
     * @var CreationVmApiModel
     */
    protected $creationVmApiModel;

    /**
     * CreationVMWrapper constructor.
     * @param VPSModel $vm
     * @param bool $skipImage
     * @throws Exception
     */
    public function __construct(VPSModel $vm, bool $skipImage = false)
    {
        $this->vm = $vm;

        $this->checkErrors($vm, $skipImage);

        $this->wrapNetworks();
        $this->wrapBlockDevices();
        $this->wrapSecurityGroups();
        $this->wrapMetadata();
        $this->wrapUserData();
        $this->wrapKeyPairs();

        $this->setApiModel($skipImage);
    }

    /**
     * @param VPSModel $vm
     * @param bool $skipImage
     * @throws Exception
     */
    protected function checkErrors(VPSModel $vm, bool $skipImage)
    {
        if (!empty($vm->getUUID()))
        {
            throw new \Exception('This action can not be executed on this VPS');
        }

        if (empty($vm->getName()))
        {
            throw new \Exception('Setup VPS name at first');
        }

        if (!$vm->getFlavor() instanceof FlavorModel)
        {
            throw new \Exception('Setup Flavor at first');
        }

        if (!$vm->getImage() instanceof ImageModel && !$skipImage)
        {
            throw new \Exception('Setup Image at first');
        }
    }

    /**
     * @throws Exception
     */
    protected function wrapNetworks()
    {
        $wrappedNetworks = [];
        foreach ($this->vm->getCreationPortsIDs() as $portId) {
            $wrappedNetworks[] = (new CreationNetworks())
                ->setPort($portId);
        }

        $this->wrappedNetwork = $wrappedNetworks;
    }

    protected function wrapBlockDevices()
    {

        $letters   = range('b', 'z');
        $bootIndex = 0;

        foreach ($this->vm->getBlockDevices() as $device)
        {
            $creationBlockDevice = new CreationBlockDevice();
            $creationBlockDevice->setSourceType(CreationBlockDevice::TYPE_VOLUME);
            $creationBlockDevice->setDestinationType(CreationBlockDevice::TYPE_VOLUME);
            $creationBlockDevice->setUUID($device->getUUID());
            $creationBlockDevice->setBootIndex($bootIndex);
            $creationBlockDevice->setDeleteOnTermination(true);
            if ($this->vm->isUseDeviceName())
            {
                $creationBlockDevice->setDeviceName(BlockDeviceDecorator::nameDecorator($letters[$bootIndex]));
            }

            $this->wrappedBlockDevices[] = $creationBlockDevice->toArray(true);
        }
    }

    protected function wrapSecurityGroups()
    {
        $creationSecurityGroups = new CreationSecurityGroups();
        foreach ((array)$this->vm->getSecurityGroup() as $group)
        {
            $creationSecurityGroups->addSecurityGroup($group);
        }

        $this->wrappedSecurityGroups = $creationSecurityGroups->getSecurityGroups();
    }

    protected function wrapMetadata()
    {
        $this->wrappedMetadata = $this->vm->getMetadata();
    }

    protected function wrapUserData()
    {
        $this->wrappedUserData = $this->vm->getCustomScript() ? base64_encode($this->vm->getCustomScript()) : null;
    }

    protected function wrapKeyPairs()
    {
        $this->wrappedKeyPairs = $this->vm->getSshKey() ? $this->vm->getSshKey()->getName() : null;
    }

    protected function setApiModel(bool $skipImage)
    {
        $this->creationVmApiModel = new CreationVmApiModel();
        $this->creationVmApiModel->setName($this->vm->getName());
        $this->creationVmApiModel->setFlavor($this->vm->getFlavor()->getUUID());
        $this->creationVmApiModel->setImage(!$skipImage ? $this->vm->getImage()->getUUID() : null);
        $this->creationVmApiModel->setNetworks($this->wrappedNetwork);
        $this->creationVmApiModel->setUserData($this->wrappedUserData);
        $this->creationVmApiModel->setKeyPair($this->wrappedKeyPairs);
        $this->creationVmApiModel->setSecurityGroups($this->wrappedSecurityGroups);
        $this->creationVmApiModel->setBlockDevices($this->wrappedBlockDevices);
        $this->creationVmApiModel->setPassword($this->vm->getPassword());
        $this->creationVmApiModel->setMetadata($this->wrappedMetadata);
        $this->creationVmApiModel->setAvailabilityZone($this->vm->getAvailabilityZone());
        $this->creationVmApiModel->setCreationPortsIds($this->vm->getCreationPortsIDs());
    }

    /**
     * @return VPSModel
     */
    public function getVm()
    {
        return $this->vm;
    }

    /**
     * @return CreationNetworks
     */
    public function getWrappedNetwork()
    {
        return $this->wrappedNetwork;
    }

    /**
     * @return array
     */
    public function getWrappedBlockDevices()
    {
        return $this->wrappedBlockDevices;
    }

    /**
     * @return array
     */
    public function getWrappedSecurityGroups()
    {
        return $this->wrappedSecurityGroups;
    }

    /**
     * @return array
     */
    public function getWrappedMetadata()
    {
        return $this->wrappedMetadata;
    }

    /**
     * @return mixed
     */
    public function getWrappedUserData()
    {
        return $this->wrappedUserData;
    }

    /**
     * @return mixed
     */
    public function getWrappedKeyPairs()
    {
        return $this->wrappedKeyPairs;
    }

    /**
     * @return CreationVmApiModel
     */
    public function getCreationVmApiModel()
    {
        return $this->creationVmApiModel;
    }


}
