<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Groups\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Groups\Forms\DeleteGroupForm;

class DeleteGroupModal extends ModalDanger implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setTitle($this->translate('title'));
        $this->setContent($this->translate('content'));
        $this->addElement(new DeleteGroupForm());
    }
}