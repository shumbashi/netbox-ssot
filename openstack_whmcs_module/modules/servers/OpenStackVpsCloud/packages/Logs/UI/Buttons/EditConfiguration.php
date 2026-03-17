<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\DropdownMenuItem\DropdownMenuItem;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Modals\EditConfigurationModal;

class EditConfiguration extends DropdownMenuItem
{
    public function loadHtml(): void
    {
        $this->setIcon('pencil');
        $this->onClick(Action::modalLoad(new EditConfigurationModal()));
    }
}
