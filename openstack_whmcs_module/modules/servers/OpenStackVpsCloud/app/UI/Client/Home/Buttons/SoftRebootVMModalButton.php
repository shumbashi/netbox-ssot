<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Modals\SoftRebootVMModal;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class SoftRebootVMModalButton extends VpsActionButtonCritical implements ClientAreaInterface, AdminAreaInterface
{
    public function loadHtml(): void
    {
        parent::loadHtml();
        $this->setImagePath('reboot.png');
        $this->setTitle("Soft Reboot");
        $this->onClick((new ModalLoad(new SoftRebootVMModal())));
    }

}