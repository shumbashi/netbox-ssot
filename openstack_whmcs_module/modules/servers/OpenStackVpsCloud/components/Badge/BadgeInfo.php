<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Badge;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Color;

/**
 * Class Form
 */
class BadgeInfo extends Badge
{
    public function __construct()
    {
        parent::__construct();
        $this->setType(Color::INFO);
    }
}
