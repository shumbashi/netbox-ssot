<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Network\Builders\MultiPort;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Network\Builders\Networking;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Network\Builders\SinglePort;
use ModulesGarden\OpenStackVpsCloud\App\Models\Servers;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration;

class NetworkBuilder extends BaseBuilder
{
    protected ?VPSModel $vm = null;

    protected ?string $fixedNetworkId = null;
    protected ?string $floatingNetworkId = null;
    protected ?array $networksList = [];

    protected ?array $productConfiguration = [];

    public function __construct(?VPSModel &$vm, array $params)
    {
        parent::__construct($params);

        $this->vm = &$vm;
        $this->networksList = Api::getInstance()->network()->listNetworks();
        $this->service = Service::findOrFail($params['serviceid']);
        $this->productConfiguration = (new ProductConfiguration($params['packageid']))->get();
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function buildFloatingNetworkId()
    {
        if ($this->floatingNetworkId) {
            return $this->floatingNetworkId;
        }

        if ($this->productConfig->getFloatingNetwork()) {
            return $this->getServiceIdFromSelectedRegionResources($this->productConfig->getFloatingNetwork(), Servers::NETWORK, Servers::AVAILABLE_NETWORKS, $this->networksList);
        }

        return null;
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function buildFixedNetworkId()
    {
        if ($this->fixedNetworkId) {
            return $this->fixedNetworkId;
        }

        return $this->getServiceIdFromSelectedRegionResources($this->productConfig->getFixedNetwork(), Servers::NETWORK, Servers::AVAILABLE_NETWORKS, $this->networksList);
    }

    public function setFixedNetworkId(?string $fixedNetworkId): NetworkBuilder
    {
        $this->fixedNetworkId = $fixedNetworkId;
        return $this;
    }

    public function setFloatingNetworkId(?string $floatingNetworkId): NetworkBuilder
    {
        $this->floatingNetworkId = $floatingNetworkId;
        return $this;
    }

    public function getNetworkingBuilder(): Networking
    {
        $this->floatingNetworkId = $this->buildFloatingNetworkId();
        $this->fixedNetworkId = $this->buildFixedNetworkId();

        if ($this->productConfiguration['single_interface']) {
            $builder = new SinglePort($this->vm);
        }
        else {
            $builder = new MultiPort($this->vm);
        }

        $builder->setFloatingNetworkId($this->floatingNetworkId);
        $builder->setFixedNetworkId($this->fixedNetworkId);
        $builder->setAddressAmount($this->productConfig->getIps());
        $builder->setDedicatedIp($this->service->dedicatedip);

        return $builder;
    }

    public function build()
    {
        $builder = $this->getNetworkingBuilder();
        $builder->build();
    }

    public function rebuild()
    {
        $builder = $this->getNetworkingBuilder();
        $builder->rebuild();
    }
}