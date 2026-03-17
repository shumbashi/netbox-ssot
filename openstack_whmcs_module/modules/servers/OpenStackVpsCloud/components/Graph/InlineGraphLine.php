<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Source\InlineGraph;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Traits\ExtendedSeries;

class InlineGraphLine extends InlineGraph
{
    use ExtendedSeries;

    public function __construct()
    {
        parent::__construct();

        $this->setType('line');
    }
}