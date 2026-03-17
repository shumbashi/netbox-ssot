<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Components\PreBlockArrayPrint\PreBlockArrayPrint;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Providers\RefreshLanguageProvider;

class RefreshLanguageForm extends Form implements AjaxComponentInterface, AdminAreaInterface
{
    protected string $provider = RefreshLanguageProvider::class;
    protected string $providerAction = CrudProvider::ACTION_CREATE;

    public function loadHtml(): void
    {
        $this->builder->createField(HiddenField::class, 'language');
        $this->builder->createField(PreBlockArrayPrint::class, 'missingLangs');
    }
}