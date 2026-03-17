<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Support\Facades;

/**
 * @method static set($name, $value, $expires = 0, $secure = NULL)
 * @method static get($name, $treatAsArray = false)
 * @method static delete($name)
 */
class Cookie extends AbstractFacade
{
    protected static function getFacadeAccessor(): string
    {
        return \ModulesGarden\OpenStackVpsCloud\Core\Services\Cookie::class;
    }
}