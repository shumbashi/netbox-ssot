<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Options;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Source\AbstractOption;

class GridOptions extends AbstractOption
{
    public bool $show;

    public function __construct(bool $show = true)
    {
        $this->show = $show;
    }

    public function getAttributes():array
    {
        return [
            'show' => $this->show,
        ];
    }
}