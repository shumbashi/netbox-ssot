<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\DropdownMenuItem;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type;

class DropdownMenuItemDelete extends DropdownMenuItem
{
    public function __construct()
    {
        parent::__construct();

        $this->setType(Type::DANGER);
        $this->setIcon('delete');
    }
}
