<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Fragments\ScheduledTasks\DataTables;

use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\AbstractRecordsListDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Status;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\Job;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\JobLog;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\QueryDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\JobNameTranslator;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Others\JobTypeLabel;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\Column as ProviderColumn;
use \Illuminate\Database\Capsule\Manager as DB;

class ServiceScheduledTasks extends DataTable implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setTitle($this->translate("title"));

        $this
            ->addColumn((new Column('job'))
                ->setTitle($this->translate('job'))
                ->setSortable()
                ->setSearchable(true, Column::TYPE_STRING))
            ->addColumn((new Column('status'))
                ->setTitle($this->translate('status'))
                ->setSortable()
                ->setSearchable())
            ->addColumn((new Column('attempts'))
                ->setTitle($this->translate('attempts'))
                ->setSearchable(true, Column::TYPE_INT))
            ->addColumn((new Column('message'))
                ->setTitle($this->translate('message'))
                ->setSearchable())
            ->addColumn((new Column('created_at'))
                ->setTitle($this->translate('created_at'))
                ->setSortable()
                ->setSearchable(true, Column::TYPE_DATE))
            ->addColumn((new Column('updated_at'))
                ->setTitle($this->translate('updated_at'))
                ->setSortable()
                ->setSearchable(true, Column::TYPE_DATE));
    }

    public function loadData(): void
    {
        $this->buildForService((int)Params::get('serviceid'));
    }

    protected function buildForService(int $serviceId): void
    {
        $jobsTable      = (new Job())->getTable();
        $jobLogsTable   = (new JobLog())->getTable();

        $dataQuery = Job::select(
            "$jobsTable.id",
            "$jobsTable.job",
            "$jobsTable.status",
            "$jobsTable.retry_count as attempts",
            DB::raw("(SELECT message FROM $jobLogsTable WHERE $jobLogsTable.job_id = $jobsTable.id ORDER BY $jobLogsTable.id DESC LIMIT 1) as message"),
            "$jobsTable.created_at",
            "$jobsTable.updated_at"
        )
            ->where("$jobsTable.rel_id", $serviceId)
            ->where("$jobsTable.rel_type", 'Hosting')
            ->where("$jobsTable.status", '!=', Status::FINISHED);

        $dataProvider = new QueryDataProvider($dataQuery);

        $dataProvider->setColumns([
            new ProviderColumn($jobsTable . '.job', ProviderColumn::TYPE_STRING, true, true),
            new ProviderColumn($jobsTable . '.status', ProviderColumn::TYPE_STRING, true, true),
            new ProviderColumn($jobsTable . '.retry_count as attempts', ProviderColumn::TYPE_INT, true, false),
            new ProviderColumn($jobsTable . '.created_at', ProviderColumn::TYPE_DATE, true, true),
            new ProviderColumn($jobsTable . '.updated_at', ProviderColumn::TYPE_DATE, true, true),
        ]);

        $dataProvider->setDefaultSorting('job', AbstractRecordsListDataProvider::SORT_DESC);

        $this->setDataProvider($dataProvider);
    }

    protected function parseDataSetRecords(): void
    {
        $this->dataSet->setFieldModifier('status', function ($fieldName, $row, $fieldValue) {
            return (new JobTypeLabel())->create($fieldValue);
        });

        $this->dataSet->setFieldModifier('job', function ($fieldName, $row, $fieldValue) {
            return (new JobNameTranslator())->format($fieldValue);
        });

        $this->dataSet->setFieldModifier('message', function ($fieldName, $row, $fieldValue) {
            return empty($fieldValue) ? "-" : $fieldValue;
        });

        $this->dataSet->modifyRecords();
    }

}