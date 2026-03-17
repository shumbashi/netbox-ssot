<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Support\Factories;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ServerConfiguration\ServerConfigurationContainer;

class ServerConfigurationFactory
{
    public static function fromServerId(int $serverId): ServerConfigurationContainer
    {
        $server = new \WHMCS\Module\Server();
        $serverParams = $server->getServerParams($serverId);

        return self::fromParams($serverParams);
    }

    public static function fromServiceId(int $serviceId): ServerConfigurationContainer
    {
        $serverId = Service::select('server')->findOrFail($serviceId)->server;
        return self::fromServerId($serverId);
    }

    public static function fromParams(array $params): ServerConfigurationContainer
    {
        $container = new ServerConfigurationContainer($params);
        $extendedJson = $container->get('serveraccesshash');
        if (!is_string($extendedJson))
        {
            return $container;
        }

        $extended = json_decode(html_entity_decode($extendedJson), true);
        if (!$extended)
        {
            return $container;
        }

        $container->set(ServerConfigurationContainer::EXTENDED_CONFIG_KEY, $extended);
        return $container;
    }
}