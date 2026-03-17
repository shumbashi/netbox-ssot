<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Modals;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Forms\CreateBackupForm;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalEdit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class CreateBackupModal extends ModalEdit implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setTitle($this->translate("title"));
        $this->addElement(new CreateBackupForm());
    }
}