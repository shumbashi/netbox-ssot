<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\DownloadFileFromForm;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalClose;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Models\Logs;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Translations\LogsTypeTranslator;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Forms\DownloadCsvForm;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Modals\ExportCsvModal;

class ExportProvider extends CrudProvider
{
    use TranslatorTrait;

    public function read()
    {
        $this->data->set('from', Logs::orderBy('date', "ASC")->first()->date);
        $this->data->set('to', Logs::orderBy('date', "DESC")->first()->date);
        $this->availableValues['types'] = (new LogsTypeTranslator)->getUsedTranslated();
    }

    public function create()
    {
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

        try {
            if ($query->count() == 0)
            {
                throw new \Exception($this->translate('noLogsForThisTherms'));
            }

            return (new Response())
                ->setSuccess($this->translate('logsExportedSuccessfully'))
                ->setActions([
                    new DownloadFileFromForm(new DownloadCsvForm(), [
                        "from" => $this->formData->get('from'),
                        "to" => $this->formData->get('to'),
                        "types" => $this->formData->get('types'),
                    ]),
                    new ModalClose(new ExportCsvModal())
                ]);

        } catch (\Exception $ex) {
            return (new Response())->setError($ex->getMessage());
        }
    }

}