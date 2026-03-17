<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders;


use ModulesGarden\OpenStackVpsCloud\App\Libs\Api\OpenStackVPS\ComputeApiService;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Services\AppConfiguration;

class RebuildVolumeVmBuilder extends BaseVmBuilder
{

    /**
     * @var VPSModel
     */
    protected $newVm;

    protected $vars = [];

    /**
     * RebuildVolumeVmBuilder constructor.
     * @param array $params
     * @param array $vars
     * @throws OSException
     * @throws \OSException
     */
    public function __construct(array $params, array $vars = [])
    {
        parent::__construct($params);

        $this->newVm      = $this->tenant->VPS();
        $this->vars = $vars;
    }

    public function buildCustomScript()
    {
        /*Rebuilding from app templates package, use provided script*/
        if ($this->vars['user_data']) {
            $this->newVm->setCustomScript($this->vars['user_data']);
            return;
        }

        /*Changing package*/
        $script = base64_decode($this->vm->getCustomScript());
        if ($script && !empty(trim($script))) {
            $this->newVm->setCustomScript($script);
            return;
        }

        /*Fallback, build a new one*/
        $script = (new CustomScriptBuilder($this->params))->build();
        $this->newVm->setCustomScript($script);
    }

    public function buildMetadata()
    {
        $metadataBuilder = new MetadataBuilder();

        /*Rebuilding from app templates package, use provided metadata*/
        if ($this->vars['metadata']) {
            $this->newVm->setMetadata($metadataBuilder->build($this->vars['metadata']));
            return;
        }

        /*Changing package*/
        $metadata = $this->vm->getMetadata();
        if (!empty($metadata)) {
            $this->newVm->setMetadata($metadata);
            return;
        }

        /*Fallback, build a new one*/
        $appConfig = (new AppConfiguration($this->params['serviceid']))
            ->getApp()
            ->getConfigArray();

        $this->newVm->setMetadata($metadataBuilder->build($appConfig['metadata']));
    }

    public function buildFlavor()
    {
        $this->newVm->setFlavor($this->vm->getFlavor());
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function buildKeyPair()
    {
        if ($this->productConfiguration->getCafKeypair())
        {
            $sshKeyBuilder = new SshKeyBuilder($this->params);
            $sshKey        = $sshKeyBuilder->build();

            $this->newVm->setSshKey($sshKey);
            $this->productCustomFields->updateFieldValue('sshKey', $sshKey->getPublic());
        }
    }

    public function buildNetwork()
    {
        $oldInterfaces = Api::getInstance()->compute()->listInterfaces($this->vm->getUUID());
        foreach ($oldInterfaces['interfaceAttachments'] as $attachment) {
            $this->newVm->addCreationPortID($attachment['port_id']);
            Api::getInstance()->compute()->deleteInterface($this->vm->getUUID(), $attachment['port_id']);
        }
    }

    public function buildName()
    {
        $this->newVm->setName($this->vm->getName());
    }

    public function buildImage()
    {
        $this->newVm->setImage($this->tenant->image($this->vars['newImageId']));
    }

    public function buildVolumes()
    {
        $volumeBuilder = new VolumeBuilder($this->params);

        $newVolume = $volumeBuilder->runBuildProcess($this->tenant->image($this->vars['newImageId']), $this->vm->getFlavor()->getDisk());
        $newVolume->create();

        $this->newVm->addBlockDevice($newVolume);

    }

    public function buildPassword()
    {
        $vmPassword = $this->vm->getPassword();
        $this->newVm->setPassword($vmPassword ?: $this->params['password']);
    }

    /**
     * @return VPSModel
     */
    public function getVm()
    {
        return $this->newVm;
    }
}
