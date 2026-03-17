<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonDelete;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Modals\DeleteLangModal;

class DeleteLangButton extends IconButtonDelete
{
    public function loadHtml(): void
    {
        $this->onClick(Action::modalLoad(new DeleteLangModal()));
    }
}