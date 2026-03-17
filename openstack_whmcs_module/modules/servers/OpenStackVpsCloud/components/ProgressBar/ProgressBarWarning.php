<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\ProgressBar;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\BackgroundColor;

class ProgressBarWarning extends ProgressBar
{
    public function __construct()
    {
        parent::__construct();
        $this->setType(BackgroundColor::WARNING);
    }
}