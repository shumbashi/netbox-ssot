<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButton;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Modals\RefreshLanguageModal;

class RefreshLanguageButton extends IconButton
{
    public function loadHtml(): void
    {
        $this->setIcon('refresh');
        $this->onClick(Action::modalLoad(new RefreshLanguageModal()));
    }
}