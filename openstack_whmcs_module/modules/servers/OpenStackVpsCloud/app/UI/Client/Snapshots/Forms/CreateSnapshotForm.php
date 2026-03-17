<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Forms;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Providers\SnapshotsProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertInfo;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;

class CreateSnapshotForm extends Form implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{

    protected string $providerAction = CrudProvider::ACTION_CREATE;
    protected string $provider = SnapshotsProvider::class;

    public function loadHtml(): void
    {
        $productConfig = new ProductConfiguration(Params::get('serviceid'));

        if ($productConfig->getSnapshotsFilesLimit() != '-1') {
            $snapshotFilesLimit = $productConfig->getSnapshotsFilesLimit() ?: '0';
            $alert = (new AlertInfo())->setText($this->translate("snapshotFilesLimit", ['snapshotFilesLimit' => $snapshotFilesLimit]));
            $this->addElement($alert);
        }

        $backupName = (new FormInputText())
            ->setName('snapshotName')
            ->required();

        $this->builder->addField($backupName);

    }
}