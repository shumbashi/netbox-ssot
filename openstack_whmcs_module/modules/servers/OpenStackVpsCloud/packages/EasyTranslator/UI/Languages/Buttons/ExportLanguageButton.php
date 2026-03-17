<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\DropdownMenuItem\DropdownMenuItem;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Modals\ExportLanguageModal;

class ExportLanguageButton extends DropdownMenuItem
{
    public function loadHtml(): void
    {
        $this->setIcon('upload');
        $this->onClick(Action::modalLoad(new ExportLanguageModal()));
    }
}