<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Forms;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Providers\BackupsProtectionStatusProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Components\Text\Text;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;

class ChangeProtectionStatusForm extends Form implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface
{
    protected string $providerAction = CrudProvider::ACTION_UPDATE;
    protected string $provider = BackupsProtectionStatusProvider::class;

    public function loadHtml(): void
    {
        $this->builder->createField(HiddenField::class, 'backup_id');

        $this->addElement( (new Text())
            ->setText($this->translate("changeProtectionStatusConfirmMess")));
    }
}