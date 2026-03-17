<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Support\Facades;

/**
 * @method static call(object $obj, string $name)
 */
class Binder extends AbstractFacade
{
    protected static function getFacadeAccessor(): string
    {
        return \ModulesGarden\OpenStackVpsCloud\Core\Services\Binder::class;
    }
}
