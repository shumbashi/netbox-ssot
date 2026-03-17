<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Support\Facades;

/**
 * @method static get(string $name, $default = null)
 */
class Config extends AbstractFacade
{
    protected static function getFacadeAccessor(): string
    {
        return \ModulesGarden\OpenStackVpsCloud\Core\Services\Config::class;
    }
}
