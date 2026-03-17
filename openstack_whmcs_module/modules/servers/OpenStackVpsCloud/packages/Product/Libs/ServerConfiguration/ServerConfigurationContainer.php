<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ServerConfiguration;

use ModulesGarden\OpenStackVpsCloud\Core\Data\Container;

class ServerConfigurationContainer extends Container
{
    const EXTENDED_CONFIG_KEY = 'extended';

    const CONFIGURATION_KEYS = [
        'server',
        'serverid',
        'serverip',
        'serverhostname',
        'serverusername',
        'serverpassword',
        'serverport',
        'serversecure',
        'serverhttpprefix',
        'serveraccesshash'
    ];

    public function createFrom(array $data): Container
    {
        foreach (self::CONFIGURATION_KEYS as $key)
        {
            $this->data[$key] = $data[$key];
        }

        return $this;
    }
}