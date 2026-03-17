<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Options;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Source\AbstractOption;

class SubTitleOptions extends TitleOptions
{
    public function __construct(string $text = '', string $align = 'center')
    {
        parent::__construct($text, $align);

        $this->style = ['fontSize' => "12px"];
    }
}