<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Pages;

use ModulesGarden\OpenStackVpsCloud\Components\Div\Div;
use ModulesGarden\OpenStackVpsCloud\Components\Grid\Grid;
use ModulesGarden\OpenStackVpsCloud\Components\Widget\Widget;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Breadcrumbs;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Factories\AppFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Support\AppTypesTranslator;

class ItemEditPage extends Div implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $item = Item::findOrFail(Request::get('id'));
        $app = AppFactory::factory($item->type);

        $editItemFormWidget = new Widget();
        $editItemFormWidget->setTitle(sprintf('%s %s', (new AppTypesTranslator())->getSingularTranslatedName($item->type), $this->translate('details')));
        $editItemFormWidget->addElement($app->getEditForm());

        $itemConfigDataTable = $app->getItemsEditTable();

        Breadcrumbs::addSuffixToLast(' - ' . html_entity_decode($item->name ?: ''));

        $grid = new Grid();
        $grid->setRows([[[$editItemFormWidget, 6], [$itemConfigDataTable, 6]]]);
        $this->addElement($grid);
    }
}