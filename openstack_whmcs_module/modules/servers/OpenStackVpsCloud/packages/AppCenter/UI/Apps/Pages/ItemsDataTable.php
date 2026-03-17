<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Pages;

use ModulesGarden\OpenStackVpsCloud\App\Libs\AppCenter\Apps\AppTemplate\AppTemplate;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonCreate;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButton;
use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonDelete;
use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonEdit;
use ModulesGarden\OpenStackVpsCloud\Components\ImageText\ImageText;
use ModulesGarden\OpenStackVpsCloud\Components\Label\LabelDanger;
use ModulesGarden\OpenStackVpsCloud\Components\Label\LabelSuccess;
use ModulesGarden\OpenStackVpsCloud\Components\Text\Text;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\Redirect;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\AbstractRecordsListDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\Column as ColumnDataProviders;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\QueryDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Helper\BuildUrl;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums\AppStatus;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Support\AppStatusTranslator;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Support\AppTypesTranslator;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Buttons\MassDeleteItemButton;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Buttons\MassEditItemButton;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Buttons\RefreshItemsButton;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Modals\CreateItemModal;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Modals\DeleteItemModal;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Modals\DuplicateItemModal;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers\ContentUrlGenerator;

class ItemsDataTable extends DataTable implements AdminAreaInterface, AjaxComponentInterface
{
    protected ?string $type = null;

    public function __construct(?string $type = null)
    {
        $this->type = $type ?? Request::get('ajaxData')['type'];
        parent::__construct();
    }

    public function loadHtml(): void
    {
        $this->setAjaxData(['type' => $this->type]);

        $this
            ->addColumn((new Column('id'))
                ->setTitle($this->translate('id'))
                ->setType(ColumnDataProviders::TYPE_INT)
                ->setSortable()
                ->setSearchable())
            ->addColumn((new Column('name'))
                ->setTitle($this->translate('name'))
                ->setType(ColumnDataProviders::TYPE_STRING)
                ->setSortable()
                ->setSearchable())
            ->addColumn((new Column('status'))
                ->setType(ColumnDataProviders::TYPE_STRING)
                ->setTitle($this->translate('status'))
                ->setSortable()
                ->setSearchable());

        $this->addToBurgerToolbar((new RefreshItemsButton()));

        $this->addActionButton((new IconButtonEdit())
            ->setName('edit')
            ->onClick($this->getEditRedirect()));

        $this->addActionButton((new IconButton())
            ->setIcon('content-copy')
            ->setName('duplicate')
            ->setTitle($this->translate('duplicate.title'))
            ->onClick(new ModalLoad(new DuplicateItemModal())));

        $this->addActionButton((new IconButtonDelete())
            ->setName('delete')
            ->onClick(new ModalLoad(new DeleteItemModal())));

        $this->addMassActionButton((new MassEditItemButton));
        $this->addMassActionButton((new MassDeleteItemButton));

        $this->addToToolbar((new ButtonCreate())
            ->setName('button_create')
            ->setTitle($this->translate('button_create'))
            ->onClick((new ModalLoad((new CreateItemModal())))->withParams([
                'type' => $this->type
            ])));
    }

    protected function getEditRedirect(): Redirect
    {
        $url = BuildUrl::getUrl('AppCenter', 'edit', [], true);
        return new Redirect($url, ['id' => 'id']);
    }

    public function loadData(): void
    {
        $dataProvider = new QueryDataProvider(Item::where('type', $this->type));

        $dataProvider->setDefaultSorting('name', AbstractRecordsListDataProvider::SORT_ASC);

        $dataProvider->setColumns([
            (new ColumnDataProviders("id", ColumnDataProviders::TYPE_INT, true, true)),
            (new ColumnDataProviders("name", ColumnDataProviders::TYPE_STRING, true, true)),
            (new ColumnDataProviders("status", ColumnDataProviders::TYPE_STRING, true, true)),
        ]);

        $this->setDataProvider($dataProvider);
    }

    public function parseDataSetRecords(): void
    {
        $this->dataSet->setFieldModifier('name', function ($fieldName, $row, $fieldValue) {
            $imageText = (new ImageText())
                ->setText(html_entity_decode($fieldValue ?: ''));

            if (empty($row['image'])) {
                return $imageText;
            }

            return $imageText->setUrl(ContentUrlGenerator::generateWithParams([
                'fileName' => $row['image']
            ]));
        });

        $this->dataSet->setFieldModifier('type', function ($fieldName, $row, $fieldValue) {
            return (new Text())->setText((new AppTypesTranslator())->getTranslated($fieldValue));
        });

        $this->dataSet->setFieldModifier('status', function ($fieldName, $row, $fieldValue) {
            $translatedStatus = (new AppStatusTranslator())->getTranslated($fieldValue);
            switch ($fieldValue) {
                case AppStatus::STATUS_ACTIVE:
                    return (new LabelSuccess())->displayAsStatusLabel()->setText($translatedStatus);
                case AppStatus::STATUS_DISABLED:
                    return (new LabelDanger())->displayAsStatusLabel()->setText($translatedStatus);
            }
        });

        $this->dataSet->modifyRecords();
    }
}