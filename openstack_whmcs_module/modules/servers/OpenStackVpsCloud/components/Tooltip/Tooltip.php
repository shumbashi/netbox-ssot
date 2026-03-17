<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Tooltip;

use ModulesGarden\OpenStackVpsCloud\Components\Icon\Icon;

/**
 * Class Form
 */
class Tooltip extends Icon
{
    public const COMPONENT = 'Tooltip';

    public function __construct()
    {
        parent::__construct();

        $this->setIcon('help-circle-outline');
    }
}
