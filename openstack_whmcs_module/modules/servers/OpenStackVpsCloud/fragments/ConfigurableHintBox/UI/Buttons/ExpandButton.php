<?php

namespace ModulesGarden\OpenStackVpsCloud\Fragments\ConfigurableHintBox\UI\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButton;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ReloadById;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class ExpandButton extends IconButton implements AdminAreaInterface
{
    protected string $widgetId;

    public function __construct(string $widgetId)
    {
        parent::__construct();

        $this->widgetId = $widgetId;
    }

    public function loadHtml(): void
    {
        $this->setIcon('chevron-down');
        $this->onClick((new ReloadById($this->widgetId))->withParams(["expand" => 1]));
    }
}