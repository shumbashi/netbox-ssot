<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Groups\Pages;

use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonCreate;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonDelete;
use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonEdit;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\Column as ColumnAlias;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\QueryDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\AbstractRecordsListDataProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Group;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Groups\Buttons\MassDeleteGroupButton;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Groups\Modals\CreateGroupModal;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Groups\Modals\DeleteGroupModal;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Groups\Modals\EditGroupModal;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\Column as ColumnDataProviders;

class GroupsDataTable extends DataTable implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->addColumn((new Column('name'))
                ->setTitle($this->translate('name'))
                ->setType(ColumnAlias::TYPE_STRING)
                ->setSortable()
                ->setSearchable())
            ->addColumn((new Column('description_value'))
                ->setType(ColumnAlias::TYPE_STRING)
                ->setTitle($this->translate('description'))
                ->setSortable()
                ->setSearchable()
            );

        $this->addActionButton((new IconButtonEdit())
            ->onClick(new ModalLoad(new EditGroupModal())));

        $this->addActionButton((new IconButtonDelete())
            ->onClick(new ModalLoad(new DeleteGroupModal())));

        $this->addMassActionButton(new MassDeleteGroupButton());

        $this->addToToolbar((new ButtonCreate())
            ->setName($this->translate('button_create'))
            ->setTitle($this->translate('button_create'))
            ->onClick(new ModalLoad(new CreateGroupModal())));
    }

    public function loadData(): void
    {
        $query = Group::select('*', 'description as description_value')->getQuery();

        $queryDataProvider = new QueryDataProvider($query);

        $queryDataProvider->setColumns([
            (new ColumnDataProviders("name", ColumnDataProviders::TYPE_STRING, true, true)),
            (new ColumnDataProviders("description_value", ColumnDataProviders::TYPE_STRING, true, true))
        ]);

        $queryDataProvider->setDefaultSorting('name', AbstractRecordsListDataProvider::SORT_ASC);

        $this->setDataProvider($queryDataProvider);
    }

    protected function parseDataSetRecords() : void
    {
        $this->dataSet->setFieldModifier('description_value', function($fieldName, $row, $fieldValue)
        {
            return $fieldValue ?: "-";
        });

        $this->dataSet->modifyRecords();
    }
}