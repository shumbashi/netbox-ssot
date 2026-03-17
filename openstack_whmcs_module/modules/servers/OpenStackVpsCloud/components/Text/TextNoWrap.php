<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Text;

class TextNoWrap extends Text
{
    public function __construct()
    {
        parent::__construct();
        $this->setSlot('noWrap', true);
    }
}