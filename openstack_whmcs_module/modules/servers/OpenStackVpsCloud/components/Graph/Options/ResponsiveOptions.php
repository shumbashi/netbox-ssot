<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Options;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Source\AbstractOption;

class ResponsiveOptions extends AbstractOption
{
    public int $breakpoint;
    public array $options;

    public function __construct(int $breakpoint = 480, array $options = [])
    {
        $this->breakpoint = $breakpoint;
        $this->options = $options;
    }

    public function getAttributes():array
    {
        return [
            'breakpoint' => $this->breakpoint,
            'options'    => $this->options,
        ];
    }
}