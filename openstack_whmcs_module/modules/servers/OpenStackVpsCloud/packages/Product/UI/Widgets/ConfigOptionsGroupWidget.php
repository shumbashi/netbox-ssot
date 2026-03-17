<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Widgets;

use ModulesGarden\OpenStackVpsCloud\Components\Widget\Widget;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\FormFieldInterface;

class ConfigOptionsGroupWidget extends Widget implements FormFieldInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->appendCss('lu-m-b-0x');
    }

    public function getName(): string
    {
        return $this->getTitle();
    }

    public function getTitle(): string
    {
        return $this->getSlot('title');
    }
};