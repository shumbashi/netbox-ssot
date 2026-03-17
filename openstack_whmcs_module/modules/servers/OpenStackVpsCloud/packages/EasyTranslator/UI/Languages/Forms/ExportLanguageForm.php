<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputGroup\FormInputGroup;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputGroupLabel\FormInputGroupLabel;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\Reload;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Providers\ImportExportProvider;

class ExportLanguageForm extends Form implements AjaxComponentInterface, AdminAreaInterface
{
    protected string $provider = ImportExportProvider::class;
    protected string $providerAction = ImportExportProvider::ACTION_EXPORT;

    public function loadHtml(): void
    {
        $this->providerActionsToValidate[] = ImportExportProvider::ACTION_EXPORT;

        $type = $this->provider()->getValueById('type');

        $this->builder->createField(Dropdown::class, 'type')
            ->setDefaultValueAsFirstOption()
            ->onChange(new Reload($this))
            ->required();

        $this->builder->createField(Dropdown::class, 'fromLanguage', false)
            ->setDefaultValueAsFirstOption()
            ->onChange(new Reload($this))
            ->required();

        $this->builder->createField(FormInputGroup::class, 'outputFileName', false)
            ->addElement((new FormInputText())->setName('fileName'))
            ->addElement((new FormInputGroupLabel())->setText(".$type"));
    }
}