<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Providers\ItemProvider;

class DuplicateItemForm extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $providerAction = ItemProvider::ACTION_DUPLICATE;
    protected string $provider = ItemProvider::class;

    public function loadHtml(): void
    {
        $this->builder->addField((new HiddenField())->setName('id'));

        $this->builder->addField((new FormInputText())
            ->setTitle($this->translate('name'))
            ->setName('name'));
    }
}