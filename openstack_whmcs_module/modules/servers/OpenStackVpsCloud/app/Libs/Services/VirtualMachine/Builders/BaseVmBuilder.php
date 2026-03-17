<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Exceptions\PostponeTaskException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductCustomFields;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Tenant;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\FlavorModel;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Services\AppConfiguration;

class BaseVmBuilder
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var ProductConfiguration
     */
    protected $productConfiguration;

    protected ?array $productConfig = [];

    /**
     * @var ProductCustomFields
     */
    protected $productCustomFields;

    /**
     * @var Tenant
     */
    protected $tenant;

    /**
     * @var VPSModel
     */
    protected $vm;

    /**
     * @var FlavorModel
     */
    protected $useFlavor;

    /**
     * VmBuilder constructor.
     * @param array $params
     * @throws OSException
     * @throws \OSException
     */
    public function __construct(array $params)
    {
        $this->params               = $params;
        $this->productConfiguration = new ProductConfiguration($params['serviceid']);
        $this->productConfig = (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration($params['pid']))->get();
        $this->productCustomFields  = new ProductCustomFields($this->params['pid'], $this->params['serviceid']);
        $this->tenant               = Factory::getTenantAsUser($this->params, $this->productConfiguration->getTenantID(), $this->productConfiguration->getDebugMode());
        $this->vm                   = $this->tenant->VPS(!empty($params['customfields']['vmID']) ? $params['customfields']['vmID'] : 'NEW');
    }

    /**
     * @throws Exception
     * @throws OSException
     * @throws \Exception
     */
    public function buildFlavor()
    {
        $flavorBuilder = new FlavorBuilder($this->params);
        $flavor        = $flavorBuilder->build();

        if ($flavor)
        {
            $oldFlavor = $this->vm->getFlavor();
            $this->vm->setFlavor($flavor, $this->params);
            if ($oldFlavor && $oldFlavor->getUUID() != $flavor->getUUID()) {
                throw new PostponeTaskException('Task postponed, awaiting resize confirmation.');
            }
        }
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function buildKeyPair()
    {
        $sshPublicKey = $this->params['customfields']['sshKey'];

        if ($this->productConfiguration->getCafKeypair() || $sshPublicKey)
        {
            $sshKeyBuilder = new SshKeyBuilder($this->params);
            $sshKey        = $sshKeyBuilder->build(trim($sshPublicKey));

            $this->vm->setSshKey($sshKey);
            $this->productCustomFields->updateFieldValue('sshKey', $sshKey->getPublic());
        }
    }

    public function buildName()
    {
        $vmName = $this->params['customfields']['domain'] ?: $this->params['domain'];
        $this->vm->setName($vmName);
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function buildNetwork()
    {
        $networkBuilder = new NetworkBuilder($this->vm, $this->params);
        $networkBuilder->build();
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function buildImage()
    {
        $imageBuilder = new ImageBuilder($this->params);
        $this->vm->setImage($imageBuilder->build());
    }

    /**
     * @return null
     * @throws Exception
     * @throws \Exception
     */
    public function buildVolumes()
    {
        if (!$this->productConfiguration->getUseVolumes())
        {
            return null;
        }

        $volumeBuilder = new VolumeBuilder($this->params);
        $newVolume = $volumeBuilder->runBuildProcess($this->vm->getImage(), $this->vm->getFlavor()->getDisk());
        $newVolume->create();

        $this->vm->addBlockDevice($newVolume);

        return null;
    }

    /**
     * @throws \Exception
     */
    public function buildCustomScript()
    {
        $customScriptBuilder = new CustomScriptBuilder($this->params);

        $appConfig = (new AppConfiguration($this->params['serviceid']))
            ->getApp()
            ->getConfigArray();

        $cloudConfig = $customScriptBuilder->build($appConfig['user_data']);

        $this->vm->setCustomScript($cloudConfig);
    }

    public function buildMetadata()
    {
        $appConfig = (new AppConfiguration($this->params['serviceid']))
            ->getApp()
            ->getConfigArray();

        $metadataBuilder = new MetadataBuilder();
        $metadata        = $metadataBuilder->build($appConfig['metadata']);

        $this->vm->setMetadata($metadata);
    }

    public function buildPassword()
    {
        $this->vm->setPassword($this->params['password']);
    }

    public function buildAvailabilityZone()
    {
        $availability_zone = ($this->productConfiguration->getAvailabilityZone() && $this->productConfiguration->getAvailabilityZone() != 'auto')? $this->productConfiguration->getAvailabilityZone() : null;
        $this->vm->setAvailabilityZone($availability_zone);
    }

    /**
     * @return VPSModel
     */
    public function getVm()
    {
        return $this->vm;
    }
}
