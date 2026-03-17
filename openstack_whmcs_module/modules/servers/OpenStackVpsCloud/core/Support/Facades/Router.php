<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Support\Facades;


/**
 * @method static getCurrentRoute : \ModulesGarden\OpenStackVpsCloud\Core\Routing\Route
 */
class Router extends AbstractFacade
{
    protected static function getFacadeAccessor(): string
    {
        return \ModulesGarden\OpenStackVpsCloud\Core\Services\Router::class;
    }
}
