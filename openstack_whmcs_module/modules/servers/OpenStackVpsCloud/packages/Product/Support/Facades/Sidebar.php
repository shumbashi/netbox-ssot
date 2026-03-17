<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Support\Facades;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\AbstractFacade;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\Sidebar as SidebarService;

/**
 * @method static getByName(string $item)
 * @method static array getAll()
 */
class Sidebar extends AbstractFacade
{
    protected static function getFacadeAccessor(): string
    {
        return SidebarService::class;
    }
}