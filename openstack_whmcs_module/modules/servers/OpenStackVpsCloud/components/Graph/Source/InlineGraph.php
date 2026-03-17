<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Source;

abstract class InlineGraph extends SparklineGraph
{
    public function __construct()
    {
        parent::__construct();

        $this->configureAsInline();
    }

}