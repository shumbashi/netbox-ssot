<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Tagger;

use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;

class Tagger extends Dropdown
{
    public function __construct()
    {
        parent::__construct();

        $this->setMultiple(true);
        $this->setAllowToCreate(true);
    }
}
