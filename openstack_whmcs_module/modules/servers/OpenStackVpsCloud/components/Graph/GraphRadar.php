<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Source\BaseGraph;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Traits\ExtendedSeries;

class GraphRadar extends BaseGraph
{
    use ExtendedSeries;

    public function __construct()
    {
        parent::__construct();

        $this->setType('radar');
    }
}
