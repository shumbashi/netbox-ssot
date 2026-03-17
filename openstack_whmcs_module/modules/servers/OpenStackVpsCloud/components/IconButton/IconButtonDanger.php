<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\IconButton;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Color;

/**
 * Class IconButton
 */
class IconButtonDanger extends IconButton
{
    public function __construct()
    {
        parent::__construct();
        $this->setType(Color::DANGER);
    }
}
