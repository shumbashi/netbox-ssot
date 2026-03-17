<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Div\Div;
use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputPassword\FormInputPassword;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;
use ModulesGarden\OpenStackVpsCloud\Components\Grid\Grid;
use ModulesGarden\OpenStackVpsCloud\Components\Switcher\Switcher;
use ModulesGarden\OpenStackVpsCloud\Components\TextArea\TextArea;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\FormFieldInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Pages\ReplacementFieldsTreeList;

class CreateItemConfigForm extends ItemConfigForm implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $providerAction = CrudProvider::ACTION_CREATE;

    public function loadHtml(): void
    {
        $this->setId('create_item_config_form');

        $div = new Div();

        $this->builder->setDefaultContainer($div);

        $this->buildFieldsInContainer();

        $grid = new Grid();
        $grid->setRows([[[$div, 6], [new ReplacementFieldsTreeList(), 3]]]);

        $this->addElement($grid);
    }
}