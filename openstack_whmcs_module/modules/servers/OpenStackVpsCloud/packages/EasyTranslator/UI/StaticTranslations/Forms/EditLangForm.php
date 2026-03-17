<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Components\TextArea\TextArea;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Fields\OriginalLang;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Providers\EditLangProvider;

class EditLangForm extends Form implements AjaxComponentInterface, AdminAreaInterface
{
    protected string $provider = EditLangProvider::class;
    protected string $providerAction = CrudProvider::ACTION_UPDATE;

    public function loadHtml(): void
    {
        $this->builder->createField(HiddenField::class, 'id');
        $this->builder->createField(OriginalLang::class, 'originalLang', false);
        $this->builder->createField(TextArea::class, 'value', false);
    }
}