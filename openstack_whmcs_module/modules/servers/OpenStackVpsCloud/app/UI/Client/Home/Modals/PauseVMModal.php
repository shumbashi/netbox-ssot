<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Modals;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Forms\PauseVMForm;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class PauseVMModal extends ModalDanger implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setContent($this->translate("confirmPauseVM"));
        $this->addElement(new PauseVMForm());
    }
}