<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Modals;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Forms\MassChangeProtectionStatusForm;
use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertInfo;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalSuccess;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class MassChangeProtectionStatusModal extends ModalSuccess implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $alert = new AlertInfo();
        $alert->setText($this->translate("protectionStatusAlertMess"));

        $this->addElement($alert);

        $this->addElement(new MassChangeProtectionStatusForm());
    }
}