<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\IconText;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type;

class IconTextWarning extends IconText
{
    public function __construct()
    {
        parent::__construct();
        $this->setType(Type::WARNING);
    }
}