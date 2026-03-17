<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\DropdownMenuItem;

use ModulesGarden\OpenStackVpsCloud\Components\Button\Button;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\DropdownMenuItemInterface;

class DropdownMenuItem extends Button implements DropdownMenuItemInterface
{
    public const COMPONENT = 'DropdownMenuItem';

    public function __construct()
    {
        parent::__construct();
        $this->setType(Type::SUCCESS);

        $this->setTranslations([
            'title',
        ]);
    }
}
