<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\MissingLangs\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButton;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\MissingLangs\Modals\SetTranslationModal;

class SetTranslationButton extends IconButton
{
    public function __construct()
    {
        parent::__construct();

        $this->setIcon('plus');
    }

    public function loadHtml(): void
    {
        $this->onClick(Action::modalLoad(new SetTranslationModal()));
    }
}