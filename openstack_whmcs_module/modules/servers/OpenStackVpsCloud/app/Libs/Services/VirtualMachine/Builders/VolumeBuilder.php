<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders;


use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\BlockDeviceModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\ImageModel;

/**
 * Class VolumeBuilder
 * @package ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders
 */
class VolumeBuilder extends BaseBuilder
{
    /**
     * @var BlockDeviceModel
     */
    protected $newVolume;

    /**
     * @param ImageModel|BlockDeviceModel $baseVolumeModel
     * @param int $flavorDiskSize
     * @return BlockDeviceModel
     * @throws Exception
     */
    public function runBuildProcess($baseVolumeModel, int $flavorDiskSize)
    {
        $this->newVolume = $this->tenant->blockDevice();

        $this->newVolume->setSize($this->productConfig->getVolumeSize() ?: $flavorDiskSize);
        $this->newVolume->setName($this->params['domain']);
        $this->newVolume->setType($this->productConfig->getVolumeType());

        $this->buildIdFromBaseVolume($baseVolumeModel);

        return $this->newVolume;

    }

    /**
     * @param $baseVolumeModel
     * @throws Exception
     */
    public function buildIdFromBaseVolume($baseVolumeModel)
    {
        /**
         * Check if size is given
         */
        if (empty($this->newVolume->getSize()))
        {
            throw new \Exception('Provide size at first');
        }

        /**
         * Setting id of base volume model
         */
        if ($baseVolumeModel instanceof ImageModel)
        {
            $this->newVolume->setImageRefID($baseVolumeModel->getUUID());

            /**
             * Checking if the product being created is not too small
             */
            if ($baseVolumeModel->getMinDisk() > $this->newVolume->getSize())
            {
                throw new \Exception('You can\'t create volume from this image because volume size is too low');
            }
        }
        elseif ($baseVolumeModel instanceof BlockDeviceModel)
        {
            $this->newVolume->setVolumeRefID($baseVolumeModel->getUUID());
        }
        else
        {
            throw new \Exception('Invalid base volume model object');
        }

    }
}