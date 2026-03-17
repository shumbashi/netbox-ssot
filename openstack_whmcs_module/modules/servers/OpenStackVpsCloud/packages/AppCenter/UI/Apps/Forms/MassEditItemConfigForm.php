<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Components\Switcher\Switcher;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Providers\ItemConfigMassProvider;

class MassEditItemConfigForm extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $provider = ItemConfigMassProvider::class;
    protected string $providerAction = CrudProvider::ACTION_UPDATE;

    public function loadHtml(): void
    {
        $this->builder->createField(HiddenField::class, 'id');
        $this->builder->addField((new Switcher())->setName('visible')->required(), true);
    }
}