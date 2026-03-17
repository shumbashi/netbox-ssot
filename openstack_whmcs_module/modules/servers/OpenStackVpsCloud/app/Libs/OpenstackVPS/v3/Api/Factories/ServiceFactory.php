<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Factories;

class ServiceFactory
{
    const INTERFACE_CLASS = 'ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services\%s';

    protected $identity = null;

    public static function getClass(string $interface)
    {
        return sprintf(self::INTERFACE_CLASS, ucfirst($interface));
    }

    public static function factory(string $interface, array $params)
    {
        $interfaceClass = self::getClass($interface);

        return new $interfaceClass(...$params);
    }
}