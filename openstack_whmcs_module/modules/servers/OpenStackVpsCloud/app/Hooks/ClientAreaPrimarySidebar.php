<?php

use ModulesGarden\OpenStackVpsCloud\App\Helpers\SidebarHelper;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Support\Facades\Sidebar;

$hookManager->register(
    function ($sidebarWhmcs) {

        if (!class_exists(Sidebar::class)) {
            return;
        }

        if (empty(Params::get('customfields.vmID'))) {
            return;
        }

        $sidebars = Sidebar::getAll();

        $sidebarHelper = new SidebarHelper();

        foreach ($sidebars as $sidebar) {
            $newPanel = [
                'label' => Translator::get($sidebar->getName()),
                'order' => 25,
                'icon' => 'fa-cog'
            ];

            $childPanel = $sidebarWhmcs->addChild($sidebar->getName(), $newPanel);

            foreach ($sidebar->getItems() as $sidebarItem) {

                if (!$sidebarHelper->isEnabled($sidebar->getName(), $sidebarItem->getName()))
                    continue;

                parse_str(parse_url($sidebarItem->getUrl(), PHP_URL_QUERY), $params);
                $isActive = $params['mg-page'] == Request::get('mg-page');

                $newItem = [
                    'label' => Translator::get($sidebarItem->getName()),
                    'uri' => $sidebarItem->getUrl(),
                    'order' => $sidebarItem->getOrder(),
                    'current' => $isActive && !is_null(Request::get('mg-page')),
                ];

                $childPanel->addChild($sidebarItem->getName(), $newItem);
            }

        }
    },
    100
);

