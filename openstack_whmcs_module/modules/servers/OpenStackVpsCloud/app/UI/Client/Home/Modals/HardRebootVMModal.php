<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Modals;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Forms\HardRebootVMForm;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class HardRebootVMModal extends ModalDanger implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setContent($this->translate("confirmHardRebootVM"));
        $this->addElement(new HardRebootVMForm());
    }
}