<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;

class NetworkModel extends BaseVpsModel
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $ownerID;

    /**
     * @var
     */
    protected $external;

    /**
     * @var
     */
    protected $subNets;


    /**
     * @return array
     */
    public function listSource()
    {
        return Api::getInstance()->network()->listNetworks();
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
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getOwnerID()
    {
        return $this->ownerID;
    }

    /**
     * @param string $ownerID
     */
    public function setOwnerID(string $ownerID)
    {
        $this->ownerID = $ownerID;
    }

    /**
     * @return mixed
     */
    public function getExternal()
    {
        return $this->external;
    }

    /**
     * @param mixed $external
     */
    public function setExternal($external)
    {
        $this->external = $external;
    }

    /**
     * @return mixed
     */
    public function getSubNets()
    {
        return $this->subNets;
    }

    /**
     * @param mixed $subNets
     */
    public function setSubNets($subNets)
    {
        $this->subNets = $subNets;
    }


}