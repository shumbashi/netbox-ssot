<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\DropdownMenuItem\DropdownMenuItem;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Modals\EditConfigurationModal;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class EditConfiguration extends DropdownMenuItem implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->setIcon('cog');
        $this->onClick(Action::modalLoad(new EditConfigurationModal()));
    }
}
