<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Modals;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Forms\MassDeleteSnapshotForm;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class MassDeleteSnapshotModal extends ModalDanger implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setContent($this->translate("deleteSnapshotsConfirmMess"));
        $this->addElement(new MassDeleteSnapshotForm());
    }

}