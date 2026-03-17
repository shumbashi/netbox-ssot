<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Providers\CreateLanguageProvider;

class CreateLanguageForm extends Form implements AjaxComponentInterface, AdminAreaInterface
{
    protected string $provider = CreateLanguageProvider::class;
    protected string $providerAction = CrudProvider::ACTION_CREATE;

    public function loadHtml(): void
    {
        $this->builder->createField(Dropdown::class, 'language', false)->required();
    }
}