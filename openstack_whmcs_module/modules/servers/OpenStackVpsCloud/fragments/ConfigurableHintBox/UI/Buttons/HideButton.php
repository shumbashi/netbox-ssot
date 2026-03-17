<?php

namespace ModulesGarden\OpenStackVpsCloud\Fragments\ConfigurableHintBox\UI\Buttons;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ReloadById;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class HideButton extends ExpandButton implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->setIcon('chevron-up');
        $this->onClick((new ReloadById($this->widgetId))->withParams(["expand" => 0]));
    }
}