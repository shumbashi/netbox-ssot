<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalEdit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Forms\EditConfigurationForm;

class EditConfigurationModal extends ModalEdit implements AjaxComponentInterface, AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->addElement(new EditConfigurationForm());
        $this->setTitle($this->translate('title'));
    }
}
