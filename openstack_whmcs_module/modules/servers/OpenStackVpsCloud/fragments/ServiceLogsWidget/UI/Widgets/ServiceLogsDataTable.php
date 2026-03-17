<?php

namespace ModulesGarden\OpenStackVpsCloud\Fragments\ServiceLogsWidget\UI\Widgets;

use ModulesGarden\OpenStackVpsCloud\Components\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Components\VisibilityWrapper\VisibilityWrapper;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\AbstractRecordsListDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\QueryDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Buttons\DeleteLogButton;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Buttons\MassDeleteButton;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Buttons\ShowDataButton;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Others\TypeLabel;
use WHMCS\Database\Capsule as DB;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Models\Logs;
use function ModulesGarden\OpenStackVpsCloud\Core\Helper\isAdmin;

class ServiceLogsDataTable extends DataTable implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setTitle($this->translate('service_logs'));

        $this->addColumn((new Column('id'))
            ->setTitle($this->translate('id'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_INT));

        $this->addColumn((new Column('message'))
            ->setTitle($this->translate('message')));

        $this->addColumn((new Column('type'))
            ->setTitle($this->translate('type'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_STRING));

        $this->addColumn((new Column('date'))
            ->setTitle($this->translate('date'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_DATE));

        if (isAdmin())
        {
            $this->addActionButton((new VisibilityWrapper(new ShowDataButton()))->disableWhen("isDataEnabled", false));
        }

        if (isAdmin() && Config::get('logs.delete_logs.enabled', true))
        {
            $this->addActionButton(new DeleteLogButton());
            $this->addMassActionButton(new MassDeleteButton());
        }
    }

    public function loadData(): void
    {
        $serviceId = Params::get('serviceid', Request::get('id', 0 ));
        $query = Logs::select('id', 'type', 'date', 'data', 'message', DB::raw("(data IS NOT NULL) AND (data != '[]') as isDataEnabled"))
            ->whereRaw("JSON_VALID(data)")
            ->whereRaw("JSON_EXTRACT(data, '$.service') = ?", $serviceId);

        $dataProv = new QueryDataProvider($query);
        $dataProv->setDefaultSorting('id', AbstractRecordsListDataProvider::SORT_DESC);
        $this->setDataProvider($dataProv);
    }

    protected function parseDataSetRecords(): void
    {
        $this->dataSet->setFieldModifier('type', function($fieldName, $row, $fieldValue) {
            return TypeLabel::create($fieldValue);
        });

        $this->dataSet->setFieldModifier('message', function($fieldName, $row, $fieldValue) {
            $replace = [];

            foreach ($row['data'] as $key => $val)
            {
                // check that the value can be cast to string
                if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString')))
                {
                    $replace[':' . $key] = $val;
                }
            }

            return strtr($this->translate($fieldValue, $replace, ['packages.logs.pages.logs_data_table']), $replace);
        });

        $this->dataSet->modifyRecords();
    }
}