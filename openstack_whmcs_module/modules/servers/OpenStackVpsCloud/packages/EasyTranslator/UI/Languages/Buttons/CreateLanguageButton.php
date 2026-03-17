<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonCreate;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Modals\CreateLanguageModal;

class CreateLanguageButton extends ButtonCreate
{
    public function loadHtml(): void
    {
        $this->onClick(new ModalLoad(new CreateLanguageModal()));
    }
}