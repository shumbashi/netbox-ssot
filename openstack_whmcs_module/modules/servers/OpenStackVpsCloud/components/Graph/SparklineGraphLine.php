<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Source\SparklineGraph;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Source\SparklineGraphExtendedSeries;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Traits\ExtendedSeries;

class SparklineGraphLine extends SparklineGraph
{
    use ExtendedSeries;

    public function __construct()
    {
        parent::__construct();

        $this->setSlot("line");
    }
}