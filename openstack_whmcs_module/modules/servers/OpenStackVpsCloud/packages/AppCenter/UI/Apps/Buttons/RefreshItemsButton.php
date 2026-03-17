<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButton;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Modals\RefreshItemsModal;

class RefreshItemsButton extends IconButton implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->setIcon('refresh')
            ->onClick(new ModalLoad(new RefreshItemsModal()));
    }
}