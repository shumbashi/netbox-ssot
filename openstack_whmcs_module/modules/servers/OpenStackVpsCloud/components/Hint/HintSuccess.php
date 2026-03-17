<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Hint;

class HintSuccess extends Hint
{
    public function __construct()
    {
        $this->setType(self::TYPE_SUCCESS);
        parent::__construct();
    }
}