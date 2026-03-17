<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButton;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Modals\RunModal;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class RunButton extends IconButton implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->setIcon('replay');
        $this->onClick(Action::modalOpen(new RunModal()));
    }
}