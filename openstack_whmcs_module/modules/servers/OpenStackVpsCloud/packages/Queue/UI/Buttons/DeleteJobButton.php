<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Modals\DeleteJobModal;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class DeleteJobButton extends IconButton\IconButtonDelete implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        parent::loadHtml();

        $this->onClick(Action::modalOpen(new DeleteJobModal()));
    }
}
