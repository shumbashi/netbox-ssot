<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\DataTable;

use ModulesGarden\OpenStackVpsCloud\Components\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Components\DropdownMenu\DropdownMenu;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\QueryDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\JobNameTranslator;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\Job;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Buttons\DeleteJobButton;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Buttons\EditConfiguration;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Buttons\MassDeleteJobButton;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Buttons\RunButton;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Buttons\ShowDataButton;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Formatters\RelatedItem;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Others\JobTypeLabel;

class Queue extends DataTable implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setId("queueDataTable");
        $this->setTitle($this->translate("title" . ucfirst(Arr::get(Request::get('ajaxData'), 'filterStatus', ""))));

        $this->addColumn((new Column('id'))
            ->setTitle($this->translate('id'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_INT));

        if (Config::get('queue.related_item.show', true))
        {
            $this->addColumn((new Column('related_item'))
                ->setTitle($this->translate('related_item')));
        }

        $this->addColumn((new Column('status'))
            ->setTitle($this->translate('status'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_DATE));
        $this->addColumn((new Column('job'))
            ->setTitle($this->translate('job'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_STRING));
        $this->addColumn((new Column('retry_count'))
            ->setTitle($this->translate('retry_count'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_STRING));

        if (Config::get('queue.retry_after.show', false))
        {
            $this->addColumn((new Column('retry_after'))
                ->setTitle($this->translate('retry_after'))
                ->setSortable()
                ->setSearchable(true, Column::TYPE_STRING));
        }

        $this->addColumn((new Column('created_at'))
            ->setTitle($this->translate('created_at'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_STRING));
        $this->addColumn((new Column('updated_at'))
            ->setTitle($this->translate('updated_at'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_STRING));

        $this->addActionButton(new ShowDataButton());
        $this->addActionButton(new RunButton());
        $this->addActionButton(new DeleteJobButton());
        $this->addMassActionButton(new MassDeleteJobButton());

        $burger = new DropdownMenu();
        $burger->addItem(new EditConfiguration());
        $this->addToToolbar($burger);
    }

    public function loadData(): void
    {
        $query = Job::getQuery();

        $dataProv = new QueryDataProvider($query);
        $dataProv->setDefaultSorting('id', QueryDataProvider::SORT_DESC);
        $this->setDataProvider($dataProv);
    }


    protected function parseDataSetRecords(): void
    {
        $this->dataSet->setFieldModifier('status', function($fieldName, $row, $fieldValue) {
            return (new JobTypeLabel())->create($fieldValue);
        });

        $this->dataSet->setFieldModifier('job', function($fieldName, $row, $fieldValue) {
            return (new JobNameTranslator())->format($fieldValue);
        });

        if (Config::get('queue.related_item.show', true))
        {
            $this->dataSet->setFieldModifier('related_item', new (Config::get('queue.related_item.formatter', RelatedItem::class))());
        }

        if (Config::get('queue.retry_after.show', false))
        {
            $this->dataSet->setFieldModifier('retry_after', function($fieldName, $row, $fieldValue)
            {
                if ($fieldValue == '0000-00-00 00:00:00')
                {
                    return $this->translate('next_cron_run');
                }

                return $fieldValue;
            });
        }

        $this->dataSet->modifyRecords();
    }
}
