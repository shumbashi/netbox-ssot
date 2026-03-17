<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Pages;

use ModulesGarden\OpenStackVpsCloud\Components\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Components\DropdownMenu\DropdownMenu;
use ModulesGarden\OpenStackVpsCloud\Components\VisibilityWrapper\VisibilityWrapper;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\AbstractRecordsListDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\QueryDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Models\Logs;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Buttons\DeleteLogButton;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Buttons\EditConfiguration;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Buttons\ExportCsvButton;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Buttons\MassDeleteButton;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Buttons\MenuDeleteButton;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Buttons\ShowDataButton;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Formatters\RelatedItem;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Formatters\TextNoWrap;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Others\TypeLabel;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\Column as ProviderColumn;
use WHMCS\Database\Capsule as DB;

class LogsDataTable extends DataTable implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $cid = 'LogsDataTable';

    public function loadHtml(): void
    {
        $this->addColumn((new Column('id_formatted'))
            ->setTitle($this->translate('id_formatted'))
            ->setSortable());

        if (Config::get('logs.related_item.show', true))
        {
            $this->addColumn((new Column('related_item'))
                ->setTitle($this->translate('related_item')));
        }
        $this->addColumn((new Column('message'))
            ->setTitle($this->translate('message'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_STRING));
        $this->addColumn((new Column('type'))
            ->setTitle($this->translate('type'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_STRING));
        $this->addColumn((new Column('date'))
            ->setTitle($this->translate('date'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_DATE));

        $this->addActionButton((new VisibilityWrapper(new ShowDataButton()))->disableWhen("isDataEnabled", false));

        $burger = new DropdownMenu();
        $burger->addItem(new EditConfiguration());
        $burger->addItem(new ExportCsvButton());

        if (Config::get('logs.delete_logs.enabled', true))
        {
            $this->addActionButton(new DeleteLogButton());
            $this->addMassActionButton(new MassDeleteButton());
            $burger->addItem(new MenuDeleteButton());
        }

        $this->addToToolbar($burger);
    }

    public function loadData(): void
    {
        $dataProv = new QueryDataProvider(
            Logs::select('id', 'id as id_formatted', 'type', 'date', 'data', 'message', DB::raw("(data IS NOT NULL) AND (data != '[]') as isDataEnabled"))
        );

        $columns = [
            new ProviderColumn('id', ProviderColumn::TYPE_INT, true, false),
            new ProviderColumn('id_formatted', ProviderColumn::TYPE_INT, false, true),
            new ProviderColumn('message', ProviderColumn::TYPE_STRING, true, true),
            new ProviderColumn('type', ProviderColumn::TYPE_STRING, true, true),
            new ProviderColumn('date', ProviderColumn::TYPE_DATE, true, true)
        ];

        if (Config::get('logs.related_item.show', true))
        {
            $columns[] = new ProviderColumn('related_item', ProviderColumn::TYPE_STRING, false, false);
        }

        $dataProv->setColumns($columns);

        $dataProv->setDefaultSorting('id', AbstractRecordsListDataProvider::SORT_DESC);
        $this->setDataProvider($dataProv);
    }

    protected function parseDataSetRecords(): void
    {
        $this->dataSet->setFieldModifier('id_formatted', new TextNoWrap());

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

            return strtr($this->translate($fieldValue), $replace);
        });

        if (Config::get('logs.related_item.show', true))
        {
            $this->dataSet->setFieldModifier('related_item', Config::get('logs.related_item.formatter', new RelatedItem()));
        }

        $this->dataSet->setFieldModifier('date', new TextNoWrap());

        $this->dataSet->modifyRecords();
    }
}
