<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Support\Facades;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\AbstractFacade;

/**
 * @method static mixed get($name, $default = null)
 * @method static array toArray()
 * @method static array getMany(array $names, $default = null)
 */
class ServerConfiguration extends AbstractFacade
{
    protected static function getFacadeAccessor(): string
    {
        return \ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ServerConfigurationService::class;
    }
}