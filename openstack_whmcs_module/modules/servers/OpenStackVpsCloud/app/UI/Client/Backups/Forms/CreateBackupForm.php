<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Forms;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Providers\BackupsProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertInfo;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;

class CreateBackupForm extends Form implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface
{
    protected string $provider = BackupsProvider::class;
    protected string $providerAction = CrudProvider::ACTION_CREATE;

    public function loadHtml(): void
    {
        $productConfig = new ProductConfiguration(Params::get('serviceid'));
        if ($productConfig->getBackupRouting() &&
            $productConfig->getBackupsFilesLimit() != '-1') {

            $alert = new AlertInfo();
            $alert->setText($this->translate("backupLimits", ["backupFilesLimit" => $productConfig->getBackupsFilesLimit()]));

            $this->addElement($alert);
        }

        $backupName = (new FormInputText())
            ->setName('backupName')->required();

        $this->builder->addField($backupName);
    }
}