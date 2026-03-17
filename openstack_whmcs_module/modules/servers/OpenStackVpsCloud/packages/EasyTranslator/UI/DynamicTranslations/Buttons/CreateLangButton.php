<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\DynamicTranslations\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonCreate;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\DynamicTranslations\Modals\CreateLangModal;

class CreateLangButton extends ButtonCreate implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->onClick(Action::modalLoad(new CreateLangModal()));
    }
}