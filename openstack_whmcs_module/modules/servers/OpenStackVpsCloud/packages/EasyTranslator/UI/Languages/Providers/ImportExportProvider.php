<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\DownloadFileFromForm;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalClose;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\Exporters\DataModels\StringData;
use ModulesGarden\OpenStackVpsCloud\Core\Exporters\JsonExporter;
use ModulesGarden\OpenStackVpsCloud\Core\Exporters\PhpReturnStatementExporter;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\Exporters\DataModels\ArrayData;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Libs\ExportLangs\ExportTypesFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\Lang;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Repositories\Langs;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\LangsImport;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Modals\ExportLanguageModal;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Forms\DownloadExportedForm;

class ImportExportProvider extends CloneLanguageProvider
{
    const ACTION_IMPORT = "import";
    const ACTION_EXPORT = "export";

    public function read()
    {
        parent::read();

        $availableTypes = $this->getExportTypes();

        $defaultFileName = $this->formData->get('fromLanguage', array_key_first($this->availableValues->get('fromLanguage'))) . "_export";

        $this->data->set('fileName', $defaultFileName);
        $this->data->set('type', $this->formData->get('type',  array_key_first($availableTypes)));

        $this->availableValues->set('type', $availableTypes);
    }

    public function import()
    {
        $request = Request::createFromGlobals();
        $formData     = $request->files->all()['formData'];

        $service = new LangsImport();
        $service->import($this->formData->get('toLanguage'), $formData['importedLanguage']);
    }

    public function export()
    {
        $sourceLanguage = $this->formData->get('fromLanguage');
        $type = $this->formData->get('type');

        if (Lang::language($sourceLanguage)->count() <= 0)
        {
            throw new \Exception("invalidSourceLanguageName");
        }

        ExportTypesFactory::findByType($type);

        return (new Response())
            ->setSuccess($this->translate('langsExportedSuccessfully'))
            ->setActions([
                new DownloadFileFromForm(new DownloadExportedForm(), [
                    "fileName"      => ($this->formData->get('fileName') ?: $sourceLanguage . "_export"). ".$type",
                    "fromLanguage"  => $sourceLanguage,
                    "type"          => $type,
                ]),
                new ModalClose(new ExportLanguageModal())
            ]);
    }

    protected function getExportTypes():array
    {
        $allTypesNames = ExportTypesFactory::getAllNames();

        return array_map(function(string $typeName) {
            return ".$typeName";
        }, array_combine($allTypesNames, $allTypesNames));
    }
}