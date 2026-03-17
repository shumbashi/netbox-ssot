<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\BlockDeviceModel;

class ChangePackageVmBuilder extends BaseVmBuilder
{
    public function buildNetwork()
    {
        $networkBuilder = new NetworkBuilder($this->vm, $this->params);
        $networkBuilder->rebuild();
    }

    public function buildVolumes()
    {
        if ($this->getMainDevice())
        {
            $volumeSize = $this->productConfiguration->getVolumeSize() ?: $this->productConfiguration->getDisk();
            if (!$volumeSize) {
                $volumeSize = $this->vm->getFlavor()->getDisk();
            }

            $mainDevice = $this->getMainDevice();

            if ($mainDevice->getSize() != $volumeSize)
            {
                return Api::getInstance()->volume()->extendVolume($mainDevice->getUUID(), $volumeSize);
            }
        }
    }

    /**
     * @return mixed|BlockDeviceModel
     */
    protected function getMainDevice()
    {
        foreach ($this->vm->getBlockDevices() as $device)
        {
            if ($device->getName() == $this->vm->getName())
            {
                return $device;
            }
        }

        return reset($this->vm->getBlockDevices());
    }
}