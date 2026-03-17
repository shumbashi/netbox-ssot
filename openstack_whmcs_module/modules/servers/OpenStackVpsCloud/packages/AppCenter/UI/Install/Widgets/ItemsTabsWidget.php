<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Install\Widgets;

use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertInfo;
use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;
use ModulesGarden\OpenStackVpsCloud\Components\Tab\Tab;
use ModulesGarden\OpenStackVpsCloud\Components\TabsWidget\TabsWidget;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums\AppProductConfig;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Factories\AppFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\ItemsGroups;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Support\AppTypesTranslator;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration;

class ItemsTabsWidget extends Container implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    private bool $hasItems = false;

    public function loadHtml(): void
    {
        $tabsWidget = new TabsWidget();

        foreach (Config::get('appCenter.Apps', []) as $class) {
            $app = AppFactory::factory($class);

            $productConfiguration = (new ProductConfiguration(Params::get('pid')))
                ->get();

            $groupIds = $productConfiguration[AppProductConfig::GROUP_DROPDOWN_NAME];
            if (!is_array($groupIds))
            {
                $groupIds = [$groupIds];
            }

            $itemsTable = (new Item)->getTable();
            $itemsGroups = (new ItemsGroups())->getTable();

            if (!ItemsGroups::whereIn('group_id', $groupIds)
                ->join($itemsTable, "$itemsGroups.item_id", '=', "$itemsTable.id")
                ->where("$itemsTable.type", $class)
                ->exists())
            {
                continue;
            }

            $this->hasItems = true;

            $tab = new Tab;
            $tab->setTitle((new AppTypesTranslator())->getTranslated($class));
            $tab->addElement($app->getTileBar());

            $tabsWidget->addTab($tab);
        }

        $this->addElement($tabsWidget);
    }

    public function postLoadHtml(): void
    {
        if ($this->hasItems)
        {
            return;
        }

        $this->addElement((new AlertInfo())->setText($this->translate("no_apps_available")));
    }
}