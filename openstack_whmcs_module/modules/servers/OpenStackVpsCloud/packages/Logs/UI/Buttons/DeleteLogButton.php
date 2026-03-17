<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Modals\DeleteLogModal;

class DeleteLogButton extends IconButton\IconButtonDelete
{
    public function loadHtml(): void
    {
        parent::loadHtml();

        $this->onClick(Action::modalOpen(new DeleteLogModal()));
    }
}
