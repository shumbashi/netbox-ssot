<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonEdit;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Modals\EditLangModal;

class EditLangButton extends IconButtonEdit
{
    public function loadHtml(): void
    {
        $this->onClick(Action::modalLoad(new EditLangModal()));
    }
}