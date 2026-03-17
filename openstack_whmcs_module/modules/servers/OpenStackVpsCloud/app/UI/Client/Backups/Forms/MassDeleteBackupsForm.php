<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Forms;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Providers\BackupsProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Http\Request;

class MassDeleteBackupsForm extends Form implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface
{
    protected string $providerAction = CrudProvider::ACTION_DELETE;
    protected string $provider = BackupsProvider::class;

    public function loadHtml(): void
    {
        $backupID = (new HiddenField())
            ->setName('id')
            ->setId('id');

        $this->builder->addField($backupID);
    }

}