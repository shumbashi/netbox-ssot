<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalEdit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Forms\EditConfigurationForm;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class EditConfigurationModal extends ModalEdit implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->addElement(new EditConfigurationForm());
        $this->setTitle($this->translate('title'));
    }
}
