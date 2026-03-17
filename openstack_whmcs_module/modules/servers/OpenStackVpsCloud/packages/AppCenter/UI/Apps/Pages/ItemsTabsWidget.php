<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Pages;

use ModulesGarden\OpenStackVpsCloud\Components\Tab\Tab;
use ModulesGarden\OpenStackVpsCloud\Components\TabsWidget\TabsWidget;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Helpers\ConfigHelper;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Factories\AppFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Support\AppTypesTranslator;

class ItemsTabsWidget extends TabsWidget implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        foreach (Config::get('appCenter.Apps', []) as $class) {
            $app = AppFactory::factory($class);

            $tab = new Tab;
            $tab->setTitle((new AppTypesTranslator())->getTranslated($class));
            $tab->addElement($app->getItemsDataTable());

            $this->addTab($tab);
        }
    }
}