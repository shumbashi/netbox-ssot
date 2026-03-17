<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Source\BaseGraph;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Traits\SimpleSeries;

class GraphDoughnut extends BaseGraph
{
    use SimpleSeries;

    public function __construct()
    {
        parent::__construct();

        $this->setType('donut');
    }
}