<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Modals;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Forms\SoftRebootVMForm;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class SoftRebootVMModal extends ModalDanger implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setContent($this->translate("confirmSoftRebootVM"));
        $this->addElement(new SoftRebootVMForm());
    }
}