<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Modals\ChangeProtectionModal;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;

class ChangeProtectionButton extends VpsActionButton implements ClientAreaInterface, AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->setImagePath('change-protection.png');
        $this->onClick((new ModalLoad(new ChangeProtectionModal())));
    }
}