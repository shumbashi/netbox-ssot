<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models;

class NetworkAddressModel extends BaseVpsModel
{
    private ?string $mac = null;
    private ?int $version = null;
    private ?string $addr = null;
    private ?string $type = null;
    private ?string $networkName = null;


    public function getMac(): ?string
    {
        return $this->mac;
    }

    public function setMac(?string $mac): self
    {
        $this->mac = $mac;
        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(?int $version): self
    {
        $this->version = $version;
        return $this;
    }

    public function getAddr(): ?string
    {
        return $this->addr;
    }

    public function setAddr(?string $addr): self
    {
        $this->addr = $addr;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getNetworkName(): ?string
    {
        return $this->networkName;
    }

    public function setNetworkName(?string $networkName): self
    {
        $this->networkName = $networkName;
        return $this;
    }
}