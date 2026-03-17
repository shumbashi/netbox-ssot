<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Alert;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Color;

class AlertInfo extends Alert
{
    public function __construct()
    {
        parent::__construct();

        $this->setType(Color::INFO);
    }
}
