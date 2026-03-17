<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Network\Builders;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;

abstract class Networking
{
    protected ?VPSModel $vm = null;

    public function __construct($vm)
    {
        $this->vm = $vm;
    }

    protected ?string $fixedNetworkId = null;
    protected ?string $floatingNetworkId = null;
    protected ?string $dedicatedIp = null;

    protected ?int $addressAmount = null;

    /*Build networking*/
    public abstract function build();

    /*Rebuild networking*/
    public abstract function rebuild();

    /*Terminate networking*/
    public abstract function terminate();

    public function setFixedNetworkId(?string $fixedNetworkId): Networking
    {
        $this->fixedNetworkId = $fixedNetworkId;
        return $this;
    }

    public function setFloatingNetworkId(?string $floatingNetworkId): Networking
    {
        $this->floatingNetworkId = $floatingNetworkId;
        return $this;
    }

    public function setAddressAmount(?int $amount): Networking
    {
        $this->addressAmount = $amount;
        return $this;
    }

    public function setDedicatedIp(?string $dedicatedIp): Networking
    {
        $this->dedicatedIp = $dedicatedIp;
        return $this;
    }
}