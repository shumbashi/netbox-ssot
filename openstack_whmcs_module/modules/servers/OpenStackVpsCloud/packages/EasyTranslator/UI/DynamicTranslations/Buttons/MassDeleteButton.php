<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\DynamicTranslations\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonDelete;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\DynamicTranslations\Modals\MassDeleteModal;

class MassDeleteButton extends IconButtonDelete
{
    public function loadHtml(): void
    {
        $this->displayWithTitle($this->translate('delete'));
        $this->onClick(Action::modalOpen(new MassDeleteModal()));
    }
}