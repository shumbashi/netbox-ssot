<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Pages;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Translators\ActionValidatorTranslator;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Buttons\CreateSnapshotButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Buttons\DeleteSnapshotButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Buttons\MassDeleteSnapshotButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Buttons\RestoreSnapshotButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Helpers\SnapshotStatusTranslator;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Providers\SnapshotsDataTableProvider;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Components\Label\LabelDanger;
use ModulesGarden\OpenStackVpsCloud\Components\Label\LabelSuccess;
use ModulesGarden\OpenStackVpsCloud\Components\Label\LabelWarning;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxAutoReloadInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxOnLoadInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\ArrayDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use function ModulesGarden\OpenStackVpsCloud\Core\Helper\isAdmin;

class SnapshotsTable extends DataTable implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface, AjaxAutoReloadInterface, AjaxOnLoadInterface
{
    const ID = 'snapshots_table';

    public function loadHtml(): void
    {
        $this->setId(self::ID);
        $this->setTitle($this->translate('title'));
        
        $this->addColumn((new Column('name'))->setTitle($this->translate('name'))->setSearchable(true))
            ->addColumn((new Column('created'))->setTitle($this->translate('created')))
            ->addColumn((new Column('status'))->setTitle($this->translate('status')));

        $this->addToToolbar(new CreateSnapshotButton())
            ->addActionButton(new RestoreSnapshotButton())
            ->addActionButton(new DeleteSnapshotButton());

        if (!isAdmin()) {
            $this->addMassActionButton(new MassDeleteSnapshotButton());
        }

        $this->loadData();
        $this->dataProvider->setSearch('');
        $this->dataSet = $this->dataProvider->getData();

        $this->setSlot('records', $this->dataSet->records);
    }

    public function loadData(): void
    {
        $provider = (new SnapshotsDataTableProvider())->loadData();
        $this->setDataProvider((new ArrayDataProvider())->setData($provider->getData()));
    }

    protected function parseDataSetRecords(): void
    {
        $this->dataSet->setFieldModifier('status', function ($fieldName, $row, $fieldValue) {
            $status = (new SnapshotStatusTranslator())->getTranslated($fieldValue);

            if (in_array($fieldValue, ['available'])) {
                return (new LabelSuccess())->setText($status)->displayAsStatusLabel();
            }

            if (in_array($fieldValue, ['deleting', 'error', 'deleted', 'error_deleting'])){
                return (new LabelDanger())->setTitle($status)->displayAsStatusLabel();
            }

            return (new LabelWarning())->setText($status)->displayAsStatusLabel();
        });

        $this->dataSet->modifyRecords();
    }
}