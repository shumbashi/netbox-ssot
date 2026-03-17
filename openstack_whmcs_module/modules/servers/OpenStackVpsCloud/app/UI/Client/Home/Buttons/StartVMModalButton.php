<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Modals\StartVMModal;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;

class StartVMModalButton extends VpsActionButtonCritical implements ClientAreaInterface, AdminAreaInterface
{
    public function loadHtml(): void
    {
        parent::loadHtml();
        $this->setImagePath('start.png');
        $this->onClick((new ModalLoad(new StartVMModal())));
    }

}