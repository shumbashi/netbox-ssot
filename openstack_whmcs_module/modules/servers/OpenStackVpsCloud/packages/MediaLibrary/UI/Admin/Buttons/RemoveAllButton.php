<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\DropdownMenuItem\DropdownMenuItemDelete;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Modals\RemoveAllModal;

class RemoveAllButton extends DropdownMenuItemDelete
{
    public function loadHtml(): void
    {
        $this->onClick(Action::modalLoad(new RemoveAllModal()));
    }
}