<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Models;


use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\Traits\SerializableModelTrait;

abstract class Model implements \JsonSerializable
{
    use SerializableModelTrait;

    /**
     * @var string
     */
    public $UUID;

    /**
     * @var string
     */
    protected $tenantID;

    public function __construct(string $tenantID, string $id = null, array $params = [])
    {
        $this->tenantID = $tenantID;
        $this->UUID      = $id;

        foreach ($params as $name => $value)
        {
            if (property_exists($this, $name))
            {
                $this->{$name} = $value;
            }
        }
    }
}