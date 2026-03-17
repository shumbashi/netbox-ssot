<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonDelete;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Modals\MassDeleteModal;

class MassDeleteButton extends IconButtonDelete
{
    public function loadHtml(): void
    {
        parent::loadHtml();

        $this->displayWithTitle($this->translate('DeleteMass'));
        $this->onClick(Action::modalOpen(new MassDeleteModal()));
    }
}