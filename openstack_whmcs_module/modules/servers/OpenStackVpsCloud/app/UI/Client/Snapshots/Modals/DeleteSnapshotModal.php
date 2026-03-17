<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Modals;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Forms\DeleteSnapshotForm;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class DeleteSnapshotModal extends ModalDanger implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setContent($this->translate("deleteSnapshotConfirmMess"));
        $this->addElement(new DeleteSnapshotForm());
    }
}