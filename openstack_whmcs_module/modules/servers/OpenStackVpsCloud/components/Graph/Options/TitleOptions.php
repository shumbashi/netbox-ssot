<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Options;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Source\AbstractOption;

class TitleOptions extends AbstractOption
{
    public string $text;
    public array $style;

    public function __construct(string $text = '')
    {
        $this->text = $text;
        $this->style = ['fontSize' => "14px"];
    }

    public function getAttributes():array
    {
        return array_filter([
            'text' => $this->text,
            'style' => $this->style,
        ]);
    }
}