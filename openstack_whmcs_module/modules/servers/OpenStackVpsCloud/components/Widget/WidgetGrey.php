<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Widget;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Decorator\Decorator;

class WidgetGrey extends Widget
{
    public function __construct()
    {
        parent::__construct();

        (new Decorator($this))->background()->setGrey();
    }
}
