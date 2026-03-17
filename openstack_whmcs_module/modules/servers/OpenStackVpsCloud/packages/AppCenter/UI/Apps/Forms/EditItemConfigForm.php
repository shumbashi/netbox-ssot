<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertInfo;
use ModulesGarden\OpenStackVpsCloud\Components\Div\Div;
use ModulesGarden\OpenStackVpsCloud\Components\Grid\Grid;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\Factories\AppConfigItemFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Pages\ReplacementFieldsTreeList;

class EditItemConfigForm extends ItemConfigForm implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $providerAction = CrudProvider::ACTION_UPDATE;

    public function loadHtml(): void
    {
        $div = new Div();

        $this->builder->setDefaultContainer($div);

        $this->builder->addFieldInContainer($div, (new HiddenField())->setName('id'));

        $this->provider()->read();

        $configItem = AppConfigItemFactory::forItemId($this->provider()->getValueById('id'));

        if (!empty($configItem->getDescription())) {
            $alert = (new AlertInfo())
                ->setText($configItem->getDescription());

            $div->addElement($alert);
        }

        $this->buildFieldsInContainer();

        $grid = new Grid();
        $grid->setRows([[[$div, 6], [new ReplacementFieldsTreeList(), 3]]]);

        $this->addElement($grid);
    }
}