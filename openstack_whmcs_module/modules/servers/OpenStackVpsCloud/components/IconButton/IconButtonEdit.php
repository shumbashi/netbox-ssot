<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\IconButton;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Color;

/**
 * Class IconButton
 */
class IconButtonEdit extends IconButtonPrimary
{
    public function __construct()
    {
        parent::__construct();
        $this->setIcon('pencil');
    }
}
