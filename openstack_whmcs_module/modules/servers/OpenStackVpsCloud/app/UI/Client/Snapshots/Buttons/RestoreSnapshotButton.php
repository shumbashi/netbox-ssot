<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Buttons;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Modals\RestoreSnapshotModal;
use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButton;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;

class RestoreSnapshotButton extends IconButton implements ClientAreaInterface, AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->setDisabled(JobManager::areCirticalBeingPerformed(Params::get('serviceid')));
        $this->setIcon('backup-restore');
        $this->onClick(new ModalLoad(new RestoreSnapshotModal()));
    }
}