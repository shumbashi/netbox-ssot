<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Pages;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Buttons\ChangeProtectionStatusButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Buttons\CreateBackupButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Buttons\DeleteBackupButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Buttons\MassChangeProtectionStatusButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Buttons\MassDeleteBackupsButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Buttons\RestoreBackupButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Providers\BackupsDataTableProvider;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Components\Label\LabelDanger;
use ModulesGarden\OpenStackVpsCloud\Components\Label\LabelSuccess;
use ModulesGarden\OpenStackVpsCloud\Components\Label\LabelWarning;
use ModulesGarden\OpenStackVpsCloud\Components\VisibilityWrapper\VisibilityWrapper;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxAutoReloadInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxOnLoadInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\ArrayDataProvider;
use function ModulesGarden\OpenStackVpsCloud\Core\Helper\isAdmin;

class BackupsTable extends DataTable implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface, AjaxAutoReloadInterface, AjaxOnLoadInterface
{
    const ID = "backupsTable";

    public function loadHtml(): void
    {
        $this->setId(self::ID);

        $this->setTitle($this->translate("title"));

        $this->addColumn((new Column('name'))->setSearchable(true)->setTitle($this->translate("name")))
            ->addColumn((new Column('created'))->setSearchable(true)->setTitle($this->translate("created")))
            ->addColumn((new Column('status'))->setSearchable(true)->setTitle($this->translate("status")))
            ->addColumn((new Column('pinned'))->setOrderable(true)->setTitle($this->translate("protected")));

        $this->addToToolbar(new CreateBackupButton());

        $restoreBackupButton = (new VisibilityWrapper(new RestoreBackupButton()))->disableWhen('disabled_by_status', '1'); //or raw_status error
        $deleteBackupButton = (new VisibilityWrapper(new DeleteBackupButton()))->disableWhen('disabled_by_pinned', '1');

        $this->addActionButton($restoreBackupButton);
        $this->addActionButton(new ChangeProtectionStatusButton());
        $this->addActionButton($deleteBackupButton);

        if (!isAdmin()) {
            $this->addMassActionButton(new MassChangeProtectionStatusButton());
            $this->addMassActionButton(new MassDeleteBackupsButton());
        }

        $this->loadData();
        $this->dataProvider->setSearch('');
        $this->dataSet = $this->dataProvider->getData();

        $this->setSlot('records', $this->dataSet->records);
    }

    public function loadData(): void
    {
        $backupsData = (new BackupsDataTableProvider())
            ->loadData();

        $provider = (new ArrayDataProvider())->setData($backupsData->getData());

        $this->setDataProvider($provider);
    }

    protected function parseDataSetRecords(): void
    {
        $this->dataSet->setFieldModifier('status', function ($fieldName, $row, $fieldValue) {
            $message = strtolower($row['status']);
            $message = $this->translate(sprintf('status.%s', $message));

            if (in_array($fieldValue, ['available', 'active'])) {
                return (new LabelSuccess())->setText($message)->displayAsStatusLabel();
            }

            if (in_array($fieldValue, ['error', 'deleting', 'error_restoring'])) {
                return (new LabelDanger())->setText($message)->displayAsStatusLabel();
            }

            //.. (‘creating’, ‘available’, ‘deleting’, ‘error’, ‘restoring’ or ‘error_restoring’)

            return (new LabelWarning())->setText($message)->displayAsStatusLabel();
        });

        $this->dataSet->setFieldModifier('pinned', function ($fieldName, $row, $fieldValue) {

            if ($fieldValue) {
                return (new LabelSuccess())
                    ->setText($this->translate("yes"))
                    ->displayAsStatusLabel();
            }

            return (new LabelDanger())
                ->setText($this->translate("no"))
                ->displayAsStatusLabel();
        });

        $this->dataSet->modifyRecords();
    }
}