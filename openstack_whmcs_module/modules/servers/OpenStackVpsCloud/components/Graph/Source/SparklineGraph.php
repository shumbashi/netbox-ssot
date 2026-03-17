<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Source;

abstract class SparklineGraph extends BaseGraph
{
    public function __construct()
    {
        parent::__construct();

        $this->configureAsSparkline();
    }

    public function setTitle(string $title):self
    {
        $this->options->title->text = $title;

        return $this;
    }

    public function setSubTitle(string $title):self
    {
        $this->options->subTitle->text = $title;

        return $this;
    }
}