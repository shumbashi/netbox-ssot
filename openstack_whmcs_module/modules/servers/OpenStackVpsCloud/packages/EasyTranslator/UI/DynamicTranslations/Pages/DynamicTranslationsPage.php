<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\DynamicTranslations\Pages;

use ModulesGarden\OpenStackVpsCloud\Components\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\QueryDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\DynamicTranslation;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\DynamicTranslations\Buttons\CreateLangButton;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\DynamicTranslations\Buttons\DeleteLangButton;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\DynamicTranslations\Buttons\EditLangButton;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\DynamicTranslations\Buttons\MassDeleteButton;

class DynamicTranslationsPage extends DataTable implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->addColumn((new Column('lang'))
            ->setTitle($this->translate('lang'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_STRING));
        $this->addColumn((new Column('regex'))
            ->setTitle($this->translate('regex'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_STRING));
        $this->addColumn((new Column('updated_at'))
            ->setTitle($this->translate('updated_at'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_DATE));

        $this->initButtons();
        $this->initToolbar();
    }

    protected function parseDataSetRecords(): void
    {
        $this->dataSet->setFieldModifier('lang', function($fieldName, $row, $fieldValue) {
            return strlen($fieldValue) < 100 ? $fieldValue : substr($fieldValue, 0, 100) . '...';
        });

        $this->dataSet->modifyRecords();
    }

    protected function initButtons(): void
    {
        $this->addActionButton(new EditLangButton());
        $this->addActionButton(new DeleteLangButton());

        $this->addMassActionButton(new MassDeleteButton());
    }

    protected function initToolbar(): void
    {
        $this->addToToolbar(new CreateLangButton());
    }

    public function loadData(): void
    {
        $query    = DynamicTranslation::select('id', 'lang', 'regex', 'updated_at');
        $dataProv = new QueryDataProvider($query);
        $dataProv->setDefaultSorting('lang', 'ASC');
        $this->setDataProvider($dataProv);
    }
}