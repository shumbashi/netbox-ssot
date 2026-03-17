<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Source\SparklineGraph;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Traits\SimpleSeries;

class SparklineGraphPie extends SparklineGraph
{
    use SimpleSeries;

    public function __construct()
    {
        parent::__construct();

        $this->setType("pie");
    }
}