<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\DropdownMenuItem\DropdownMenuItem;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Modals\RefreshLangsModal;

class RefreshLangsButton extends DropdownMenuItem
{
    public function loadHtml(): void
    {
        $this->setIcon('refresh');
        $this->onClick(Action::modalLoad(new RefreshLangsModal()));
    }
}