<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Modal;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Forms\RunForm;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class RunModal extends Modal\ModalDanger implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->addElement(new RunForm());
        $this->setTitle($this->translate('title'));
        $this->setContent($this->translate('description'));
    }
}