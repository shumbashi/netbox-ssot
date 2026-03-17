<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\IconText;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type;

class IconTextPrimary extends IconText
{
    public function __construct()
    {
        parent::__construct();
        $this->setType(Type::PRIMARY);
    }
}