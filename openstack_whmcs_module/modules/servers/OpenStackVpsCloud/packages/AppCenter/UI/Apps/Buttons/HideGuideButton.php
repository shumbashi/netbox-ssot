<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButton;

class HideGuideButton extends IconButton
{
    public function loadHtml(): void
    {
        $this->setIcon('close');
    }
}