<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonDelete;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Modals\MassDeleteModal;

class MassDeleteButton extends IconButtonDelete
{
    public function loadHtml(): void
    {
        $this->displayWithTitle($this->translate('delete'));
        $this->onClick(Action::modalOpen(new MassDeleteModal()));
    }
}
