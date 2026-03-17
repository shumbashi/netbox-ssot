<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\DropdownMenuItem\DropdownMenuItemDelete;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Modals\MenuDeleteModal;

class MenuDeleteButton extends DropdownMenuItemDelete
{
    public function loadHtml(): void
    {
        $this->onClick(Action::modalLoad(new MenuDeleteModal()));
    }
}