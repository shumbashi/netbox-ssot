<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Support\Facades;


class Smarty extends AbstractFacade
{
    protected static function getFacadeAccessor(): string
    {
        return \ModulesGarden\OpenStackVpsCloud\Core\Services\Smarty::class;
    }
}
