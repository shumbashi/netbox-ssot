<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\UsageWidget;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Options\ColorsOptions;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Series\SimpleSeries;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\SparklineGraphPie;
use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;

class UsageWidgetGraph extends UsageWidget
{
    public function __construct()
    {
        parent::__construct();

        $this->disableLabels();
    }

    public function visualisationElementSlotBuilder():AbstractComponent
    {
        $graphPie = new SparklineGraphPie();

        $graphPie->addSeries(new SimpleSeries($this->usage));
        $graphPie->addSeries(new SimpleSeries($this->limit - $this->usage));

        $options = $graphPie->getOptions();
        $options->colors = new ColorsOptions(['#DC3545', '#dfe2e8']);

        return $graphPie;
    }
}