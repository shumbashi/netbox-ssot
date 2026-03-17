<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Source\SparklineGraph;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Traits\ExtendedSeries;

class SparklineGraphArea extends SparklineGraph
{
    use ExtendedSeries;

    public function __construct()
    {
        parent::__construct();

        $this->setType("area");
    }
}