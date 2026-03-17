<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Http\BinaryFileResponse;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Models\Logs;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\Exporters\CsvExporter;
use ModulesGarden\OpenStackVpsCloud\Core\Exporters\DataModels\Query;

class DownloadCsvProvider extends CrudProvider
{
    public const LOGS_EXPORTED_FILE_NAME = 'logs.csv';

    public function read()
    {
        try {
            $query = Logs::select('id', 'type', 'date', 'message', 'data');

            if ($fromDate = $this->formData->get('from'))
            {
                $query->where('date', ">=", (new \DateTime($fromDate))->format('Y-m-d'));
            }

            if ($toDate = $this->formData->get('to'))
            {
                $query->where('date', "<=", (new \DateTime($toDate))->modify('+1 day')->format('Y-m-d'));
            }

            if ($types = $this->formData->get('types'))
            {
                $query->whereIn('type', $types);
            }

            $fileName = tempnam("php://temp", 'logs');

            (new CsvExporter(new Query($query)))->write(new \SplFileInfo($fileName));

            return (new BinaryFileResponse(new \SplFileInfo($fileName)))
                ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, self::LOGS_EXPORTED_FILE_NAME)
                ->deleteFileAfterSend(true);
        }
        catch (\Exception $ex)
        {
            return (new Response())->setError($ex->getMessage());
        }
    }
}