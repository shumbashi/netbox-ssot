<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\DropdownMenuItem\DropdownMenuItem;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Modals\CloneLanguageModal;

class CloneLanguageButton extends DropdownMenuItem
{
    public function loadHtml(): void
    {
        $this->setIcon('content-copy');
        $this->onClick(Action::modalLoad(new CloneLanguageModal()));
    }
}