<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\Traits\SerializableModelTrait;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\Creation\Serializer;

class BaseVpsModel extends Serializer implements \JsonSerializable
{
    use SerializableModelTrait;

    /**
     * @var string
     */
    protected $tenantID;

    /**
     * @var string
     */
    protected $UUID;

    /**
     * BaseVpsModel constructor.
     * @param string|null $tenantID
     * @param string|null $UUID
     * @param array $params
     */
    public function __construct(string $tenantID = null, string $UUID = null, array $params = [])
    {
        $this->UUID     = $UUID;
        $this->tenantID = $tenantID;

        foreach ($params as $name => $value)
        {
            if (property_exists($this, $name))
            {
                $this->{$name} = $value;
            }
        }
    }

    /**
     * @return string
     */
    public function getTenantID()
    {
        return $this->tenantID;
    }

    /**
     * @param string $tenantID
     */
    public function setTenantID(string $tenantID)
    {
        $this->tenantID = $tenantID;
    }

    /**
     * @return string
     */
    public function getUUID()
    {
        return $this->UUID;
    }

    /**
     * @param string $UUID
     */
    public function setUUID(string $UUID)
    {
        $this->UUID = $UUID;
    }
}