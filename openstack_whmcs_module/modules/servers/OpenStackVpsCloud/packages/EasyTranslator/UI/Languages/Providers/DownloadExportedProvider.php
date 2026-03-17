<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Exporters\DataModels\ArrayData;
use ModulesGarden\OpenStackVpsCloud\Core\Http\BinaryFileResponse;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Libs\ExportLangs\ExportTypesFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Repositories\Langs;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadExportedProvider extends CrudProvider
{
    public function read()
    {
        $type           = $this->formData->get('type');
        $sourceLanguage = $this->formData->get('fromLanguage');
        $fileName       = $this->formData->get('fileName');

        $fileFullPath = tempnam("php://temp", 'langs');

        if (empty($fileFullPath))
        {
            throw new \Exception("File Not Found");
        }

        if (empty($type))
        {
            throw new \Exception("Empty type provided");
        }

        if (empty($sourceLanguage))
        {
            throw new \Exception("Empty source language provided");
        }

        $exporter = ExportTypesFactory::findByType($type);

        $exporter->export(new ArrayData(Langs::getCombined($sourceLanguage)), $fileFullPath);

        return (new BinaryFileResponse(new \SplFileInfo($fileFullPath)))
            ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName)
            ->deleteFileAfterSend(true);
    }
}