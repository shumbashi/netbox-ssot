<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Services;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ServerConfiguration\ServerConfigurationContainer;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Support\Factories\ServerConfigurationFactory;

class ServerConfigurationService
{
    protected static ServerConfigurationContainer $container;

    public function __construct()
    {
        $this->load();
    }

    protected function load(): void
    {
        static::$container = ServerConfigurationFactory::fromParams(Params::all());
    }

    public function get(string $key, $default = null)
    {
        return static::$container->get($key, $default);
    }

    public function toArray(): array
    {
        return static::$container->toArray();
    }

    public function getMany(array $names, $default = null): array
    {
        return static::$container->getMany($names, $default);
    }
}