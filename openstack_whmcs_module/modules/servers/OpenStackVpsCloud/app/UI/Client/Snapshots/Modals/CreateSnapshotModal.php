<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Modals;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Forms\CreateSnapshotForm;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalEdit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class CreateSnapshotModal extends ModalEdit implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->addElement(new CreateSnapshotForm());
    }
}