<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Support\Facades;

class Dispatcher extends AbstractFacade
{
    protected static function getFacadeAccessor(): string
    {
        return \ModulesGarden\OpenStackVpsCloud\Core\Events\Dispatcher::class;
    }
}
