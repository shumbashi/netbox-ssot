<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Support\Facades;

class Store extends AbstractFacade
{
    protected static function getFacadeAccessor(): string
    {
        return \ModulesGarden\OpenStackVpsCloud\Core\Services\Store::class;
    }
}