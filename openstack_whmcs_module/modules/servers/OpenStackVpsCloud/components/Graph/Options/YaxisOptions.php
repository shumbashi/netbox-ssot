<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Options;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Constants\Formatters;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Source\AbstractOption;

class YaxisOptions extends AbstractOption
{
    public bool $show;
    public string $title;

    public function __construct(bool $show = true, string $title = '')
    {
        $this->show  = $show;
        $this->title = $title;
    }

    public function getAttributes():array
    {
        return [
            'show'   => $this->show,
            'title'  => [
                'text' => $this->title
            ],
            'labels' => [
                'formatter' => Formatters::SHOW_ONLY_INTEGERS
            ],
        ];
    }
}