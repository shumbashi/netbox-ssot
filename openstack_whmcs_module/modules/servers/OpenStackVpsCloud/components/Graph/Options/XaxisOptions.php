<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Options;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Source\AbstractOption;

class XaxisOptions extends AbstractOption
{
    public array $categories;
    public int $tickAmount = 30;

    public function __construct($categories = [])
    {
        $this->categories = $categories;
    }

    public function getAttributes():array
    {
        return [
            'categories' => $this->categories,
            'tickAmount' => $this->tickAmount,
        ];
    }
}