<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonEdit;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Modals\ShowDataModal;

class ShowDataButton extends IconButtonEdit
{
    public function loadHtml(): void
    {
        $this->setIcon('information');
        $this->onClick(Action::modalLoad(new ShowDataModal()));
    }
}
