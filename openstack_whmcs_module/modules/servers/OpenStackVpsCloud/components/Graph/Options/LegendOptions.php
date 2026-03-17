<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Options;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Source\AbstractOption;

class LegendOptions extends AbstractOption
{
    public bool $show;
    public bool $showForSingleSeries;
    public string $position;
    public string $horizontalAlign;
    public array $markers;

    public function __construct(bool $show = true, string $position = 'top', string $horizontalAlign = 'center', bool $showForSingleSeries = true)
    {
        $this->show                = $show;
        $this->showForSingleSeries = $showForSingleSeries;
        $this->position            = $position;
        $this->horizontalAlign     = $horizontalAlign;
        $this->markers             = ['shape' => "circle"];
    }

    public function getAttributes():array
    {
        return [
            'show'                => $this->show,
            'showForSingleSeries' => $this->showForSingleSeries,
            'position'            => $this->position,
            'horizontalAlign'     => $this->horizontalAlign,
            'markers'             => $this->markers,
        ];
    }
}