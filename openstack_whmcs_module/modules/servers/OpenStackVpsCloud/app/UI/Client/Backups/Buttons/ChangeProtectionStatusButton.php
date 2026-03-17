<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Buttons;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Modals\ChangeProtectionStatusModal;
use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButton;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class ChangeProtectionStatusButton extends IconButton implements AdminAreaInterface, ClientAreaInterface
{
    public function loadHtml(): void
    {
        $this->setIcon('cog');
        $this->onClick(new ModalLoad(new ChangeProtectionStatusModal()));
    }
}