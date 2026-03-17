<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\MissingLangs\Pages;

use ModulesGarden\OpenStackVpsCloud\Components\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\QueryDataProvider;
use ModulesGarden\OpenStackVpsCloud\Components\DropdownMenu\DropdownMenu;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\MissingLang;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\MissingLangs\Buttons\ExportMissingTranslationsButton;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\MissingLangs\Buttons\SetTranslationButton;

class MissingLangsPage extends DataTable implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->addColumn((new Column('lang'))
            ->setTitle($this->translate('lang'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_STRING));
        $this->addColumn((new Column('language'))
            ->setTitle($this->translate('language'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_STRING));
        $this->addColumn((new Column('updated_at'))
            ->setTitle($this->translate('updated_at'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_DATE));

        $this->initButtons();
    }

    protected function initButtons(): void
    {
        $this->addActionButton(new SetTranslationButton());
    }

    public function loadData(): void
    {
        $query    = MissingLang::query();
        $dataProv = new QueryDataProvider($query);
        $dataProv->setDefaultSorting('lang', 'ASC');
        $this->setDataProvider($dataProv);
    }

    protected function parseDataSetRecords(): void
    {
        $this->dataSet->setFieldModifier('language', function($fieldName, $row, $fieldValue) {
            return ucfirst($fieldValue);
        });

        $this->dataSet->modifyRecords();
    }
}