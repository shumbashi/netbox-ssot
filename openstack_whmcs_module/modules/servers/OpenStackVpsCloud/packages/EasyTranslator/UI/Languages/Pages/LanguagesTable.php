<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Pages;

use ModulesGarden\OpenStackVpsCloud\Components\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Components\DropdownMenu\DropdownMenu;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\QueryDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\Lang;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Buttons\CloneLanguageButton;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Buttons\ImportLanguageButton;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Buttons\ExportLanguageButton;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Buttons\SettingsButton;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Buttons\CreateLanguageButton;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Buttons\DeleteLanguageButton;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Buttons\EditLanguageButton;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Buttons\MassDeleteButton;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Buttons\RefreshLanguageButton;

class LanguagesTable extends DataTable implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $uniqueColumnName = 'originalLanguage';

    public function loadHtml(): void
    {
        $this->addColumn((new Column('language'))
            ->setTitle($this->translate('language'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_STRING));
        $this->addColumn((new Column('created_at'))
            ->setTitle($this->translate('created_at'))
            ->setSortable()
            ->setSearchable(true, Column::TYPE_DATE));

        $this->initButtons();
        $this->initToolbar();
    }

    protected function initButtons(): void
    {
        $this->addActionButton(new RefreshLanguageButton());
        $this->addActionButton(new EditLanguageButton());
        $this->addActionButton(new DeleteLanguageButton());

        $this->addMassActionButton(new MassDeleteButton());
    }

    protected function initToolbar(): void
    {
        $this->addToToolbar(new CreateLanguageButton());

        $burger = new DropdownMenu();
        $burger->addItem(new CloneLanguageButton());
        $burger->addItem(new ImportLanguageButton());
        $burger->addItem(new ExportLanguageButton());

        $this->addToToolbar($burger);
    }

    public function loadData(): void
    {
        $query    = Lang::select('language', 'language as originalLanguage', 'created_at')->groupBy('language');
        $dataProv = new QueryDataProvider($query);
        $dataProv->setDefaultSorting('language', 'ASC');
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