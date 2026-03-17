<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Text;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Decorator\Decorator;

class TextBold extends Text
{
    public function __construct()
    {
        parent::__construct();

        (new Decorator($this))->font()->setBoldWeight();
    }
}