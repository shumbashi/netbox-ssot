<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Models\CacheResources;

abstract class BaseCacheModel
{
    /**
     * @var string
     */
    protected $UUID;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param array $props
     */
    public function fill(array $props = []): void
    {
        foreach ($props as $propName => $value)
        {
            if (property_exists($this, $propName))
            {
                $this->{$propName} = $value;
            }
        }
    }

    /**
     * @return string
     */
    public function getUUID(): string
    {
        return $this->UUID;
    }

    /**
     * @param string $UUID
     */
    public function setUUID(string $UUID): void
    {
        $this->UUID = $UUID;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}