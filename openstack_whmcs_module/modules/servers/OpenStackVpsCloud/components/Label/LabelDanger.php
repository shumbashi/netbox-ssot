<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Label;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Color;

/**
 * Class Form
 */
class LabelDanger extends Label
{
    public function __construct()
    {
        parent::__construct();
        $this->setType(Color::DANGER);
    }
}
