<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonInfo;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class ServerConfiguration extends ButtonInfo implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->setTitle($this->translate('button.title'));
        $this->setIcon('cog');
    }
}