<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\Creation;

class CreationVmApiModel
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $flavor;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var string
     */
    protected $userData = null;

    /**
     * @var
     */
    protected $keyPair = null;

    /**
     * @var array
     */
    protected $securityGroups = [];

    /**
     * @var array
     */
    protected $blockDevices = [];

    /**
     * @var string
     */
    protected $password = null;

    /**
     * @var array
     */
    protected $metadata = null;

    protected array $networks = [];

    /**
     * @var string|null
     */
    protected $availability_zone = null;

    public function getNetworks()
    {
        return $this->networks;
    }

    public function setNetworks(array $networks)
    {
        $this->networks = $networks;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getFlavor()
    {
        return $this->flavor;
    }

    /**
     * @param string $flavor
     */
    public function setFlavor(string $flavor)
    {
        $this->flavor = $flavor;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image = null)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getUserData()
    {
        return $this->userData;
    }

    /**
     * @param string $userData
     */
    public function setUserData(string $userData = null)
    {
        $this->userData = $userData;
    }


    public function getKeyPair()
    {
        return $this->keyPair;
    }


    public function setKeyPair($keyPair = null)
    {
        $this->keyPair = $keyPair;
    }


    /**
     * @return array
     */
    public function getSecurityGroups()
    {
        return $this->securityGroups;
    }

    /**
     * @param array $securityGroups
     */
    public function setSecurityGroups(array $securityGroups = [])
    {
        $this->securityGroups = $securityGroups;
    }

    /**
     * @return array
     */
    public function getBlockDevices()
    {
        return $this->blockDevices;
    }

    /**
     * @param array $blockDevices
     */
    public function setBlockDevices(array $blockDevices = [])
    {
        $this->blockDevices = $blockDevices;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password = null)
    {
        $this->password = $password;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param array $metadata
     */
    public function setMetadata(array $metadata = null)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return string|null
     */
    public function getAvailabilityZone()
    {
        return $this->availability_zone;
    }

    /**
     * @param string|null $availability_zone
     */
    public function setAvailabilityZone($availability_zone)
    {
        $this->availability_zone = $availability_zone;
    }

    /**
     * @return array
     */
    public function getCreationPortsIds(): array
    {
        return $this->creationPortsIds;
    }

    /**
     * @param array $creationPortsIds
     */
    public function setCreationPortsIds(array $creationPortsIds): void
    {
        $this->creationPortsIds = $creationPortsIds;
    }
}