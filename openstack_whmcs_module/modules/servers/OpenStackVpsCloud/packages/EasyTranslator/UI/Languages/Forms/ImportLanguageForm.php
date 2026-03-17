<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\UploadField\UploadField;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\LangsImport;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Providers\ImportExportProvider;

class ImportLanguageForm extends Form implements AjaxComponentInterface, AdminAreaInterface
{
    protected string $provider = ImportExportProvider::class;
    protected string $providerAction = ImportExportProvider::ACTION_IMPORT;

    public function loadHtml(): void
    {
        $this->providerActionsToValidate[] = ImportExportProvider::ACTION_IMPORT;

        $this->builder->createField(UploadField::class, 'importedLanguage')
            ->setAllowedFileTypes(array_map(fn($extension): string => "." . $extension, LangsImport::getAllowedExtensions()))
            ->required();
        $this->builder->createField(Dropdown::class, 'toLanguage', false)->required();
    }
}