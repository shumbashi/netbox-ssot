<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Modals;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Forms\RunForm;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;

class RunModal extends ModalDanger implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setContent($this->translate("confirmRun"));
        $this->addElement(new RunForm());
    }
}