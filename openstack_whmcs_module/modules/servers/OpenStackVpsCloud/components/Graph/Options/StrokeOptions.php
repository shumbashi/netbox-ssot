<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Options;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Source\AbstractOption;

class StrokeOptions extends AbstractOption
{
    public int $width;

    public function __construct(int $width = 2)
    {
        $this->width = $width;
    }

    public function getAttributes():array
    {
        return [
            'width' => $this->width
        ];
    }
}