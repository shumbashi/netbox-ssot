<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Buttons;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Modals\CreateBackupModal;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonCreate;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;

class CreateBackupButton extends ButtonCreate implements AdminAreaInterface, ClientAreaInterface
{
    public function loadHtml(): void
    {
        $this->setDisabled(JobManager::areCirticalBeingPerformed(Params::get('serviceid')));
        $this->onClick((new ModalLoad(new CreateBackupModal())));
    }
}