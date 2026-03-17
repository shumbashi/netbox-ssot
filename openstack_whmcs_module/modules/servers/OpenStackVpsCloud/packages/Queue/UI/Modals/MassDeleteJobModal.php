<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Modal;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Forms\MassDeleteJobForm;

class MassDeleteJobModal extends Modal\ModalDanger implements AjaxComponentInterface, AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->addElement(new MassDeleteJobForm());
        $this->setTitle($this->translate('title'));
        $this->setContent($this->translate('description'));
    }
}