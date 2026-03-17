<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Services;

use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Services\Sidebar as CoreSidebarService;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Sidebar\Item as SidebarItem;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Sidebar\Sidebar as SidebarContainer;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Hosting;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Enums\ConfigSettings;

class Sidebar extends CoreSidebarService
{
    protected function load()
    {
        if (Request::get('action', '') != 'productdetails')
        {
            return;
        }

        $service = Hosting::find(Request::get('id'));

        if ($service->product->servertype != ModuleConstants::getModuleName())
        {
            return;
        }

        if ($service->domainstatus != 'Active')
        {
            return;
        }

        $this->build(\ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get(ConfigSettings::SIDEBARS, []));
    }

    protected function build(array $data = [])
    {
        $currentPage = Request::get('mg-page');

        foreach ($data as $sidebarName => $sidebarContent)
        {
            $sidebar = new SidebarContainer($sidebarName);

            foreach ($sidebarContent as $itemName => $item)
            {
                $itemUrl = is_callable($item['uri']) ? call_user_func($item['uri']) : $item['uri'];
                $sidebarItem = new SidebarItem($itemName, $itemUrl, $item['order']);

                $sidebarItem->setActive(rtrim($itemName, 's') == $currentPage);

                $sidebar->addItem($sidebarItem);
            }

            $this->addSidebar($sidebar);
        }
    }
}