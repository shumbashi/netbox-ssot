<?php

namespace ModulesGarden\OpenStackVpsCloud\Fragments\ConfigurableHintBox\UI\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Components\Switcher\Switcher;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Fragments\ConfigurableHintBox\UI\Providers\ConfigurationProvider;

class EditConfigurationForm extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $provider = ConfigurationProvider::class;
    protected string $providerAction = CrudProvider::ACTION_UPDATE;

    public function loadHtml(): void
    {
        $this->builder->createField(Switcher::class, 'hideHintBox', true);
        $this->builder->createField(HiddenField::class, 'widgetId');
    }
}