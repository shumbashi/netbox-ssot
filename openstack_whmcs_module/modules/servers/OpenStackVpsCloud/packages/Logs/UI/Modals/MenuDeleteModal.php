<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Forms\MenuDeleteForm;

class MenuDeleteModal extends ModalDanger implements AjaxComponentInterface, AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->addElement(new MenuDeleteForm());
        $this->setTitle($this->translate('title'));
    }
}