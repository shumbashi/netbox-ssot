<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\DropdownMenuItem\DropdownMenuItem;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Modals\ImportLanguageModal;

class ImportLanguageButton extends DropdownMenuItem
{
    public function loadHtml(): void
    {
        $this->setIcon('download');
        $this->onClick(Action::modalLoad(new ImportLanguageModal()));
    }
}