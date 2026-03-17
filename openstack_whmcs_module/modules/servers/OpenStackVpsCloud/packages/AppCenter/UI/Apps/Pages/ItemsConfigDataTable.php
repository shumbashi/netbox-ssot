<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Pages;

use ModulesGarden\RecurringContractBilling\Components\Text\Text;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonCreate;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonDelete;
use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonEdit;
use ModulesGarden\OpenStackVpsCloud\Components\Label\Label;
use ModulesGarden\OpenStackVpsCloud\Components\Label\LabelSuccess;
use ModulesGarden\OpenStackVpsCloud\Components\Switcher\Switcher;
use ModulesGarden\OpenStackVpsCloud\Components\VisibilityWrapper\VisibilityWrapper;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\QueryDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\ItemConfig;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Buttons\MassDeleteItemConfigButton;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Buttons\MassEditItemConfigButton;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Modals\CreateItemConfigModal;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Modals\DeleteItemConfigModal;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Modals\EditItemConfigModal;

class ItemsConfigDataTable extends DataTable implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setId('items_config_data_table');

        $this->setTitle($this->translate('title'));

        $this->addColumn((new Column('setting'))
            ->setTitle($this->translate('name'))
            ->setSortable()
            ->setSearchable());

        $this->addColumn((new Column('visible'))
            ->setTitle($this->translate('visible'))
            ->setSortable()
            ->setSearchable());

        $this->addActionButton((new IconButtonEdit())
            ->onClick(new ModalLoad(new EditItemConfigModal())));

        $deleteButton = (new IconButtonDelete())
            ->onClick(new ModalLoad(new DeleteItemConfigModal()));

        $this->addActionButton((new VisibilityWrapper($deleteButton))->disableWhen('protected', 1));

        $this->addToToolbar((new ButtonCreate())
            ->setName('button_create')
            ->setTitle($this->translate('button_create'))
            ->onClick(new ModalLoad(new CreateItemConfigModal())));

        $this->addMassActionButton(new MassEditItemConfigButton());
        $this->addMassActionButton(new MassDeleteItemConfigButton());
    }

    public function loadData(): void
    {
        $query = ItemConfig::where('item_id', Request::get('id'));

        $dataProvider = new QueryDataProvider($query);
        $this->setDataProvider($dataProvider);
    }
    public function parseDataSetRecords(): void
    {
        $this->dataSet->setFieldModifier('setting', function ($fieldName, $row, $fieldValue) {
            if (!empty($fieldValue) && is_string($fieldValue)) {
                return html_entity_decode($fieldValue);
            }

            return $fieldValue;
        });

        $this->dataSet->setFieldModifier('value', function ($fieldName, $row, $fieldValue) {
            if (!empty($fieldValue) && is_string($fieldValue)) {
                return html_entity_decode($fieldValue);
            }

            return $fieldValue;
        });

        $this->dataSet->setFieldModifier('visible', function ($fieldName, $row, $fieldValue) {
            $class = $fieldValue ? LabelSuccess::class : Label::class;
            return (new $class)
                ->setText($this->translate($fieldValue ? 'yes' : 'no'))
                ->displayAsStatusLabel();
        });

        $this->dataSet->modifyRecords();
    }
}