<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Modals;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Forms\RestoreSnapshotForm;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class RestoreSnapshotModal extends ModalDanger implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setContent($this->translate("restoreSnapshotConfirmMess"));
        $this->addElement(new RestoreSnapshotForm());
    }
}