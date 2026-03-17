<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonDelete;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Modals\DeleteLanguageModal;

class DeleteLanguageButton extends IconButtonDelete
{
    public function loadHtml(): void
    {
        $this->onClick(Action::modalLoad(new DeleteLanguageModal()));
    }
}