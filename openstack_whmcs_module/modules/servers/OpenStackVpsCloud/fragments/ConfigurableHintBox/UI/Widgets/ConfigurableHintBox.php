<?php

namespace ModulesGarden\OpenStackVpsCloud\Fragments\ConfigurableHintBox\UI\Widgets;

use ModulesGarden\OpenStackVpsCloud\Components\DropdownMenu\DropdownMenu;
use ModulesGarden\OpenStackVpsCloud\Components\HintsBox\HintsBox;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ToolbarTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Data\Container;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Fragments\ConfigurableHintBox\UI\Buttons\EditConfiguration;
use ModulesGarden\OpenStackVpsCloud\Fragments\ConfigurableHintBox\UI\Buttons\ExpandButton;
use ModulesGarden\OpenStackVpsCloud\Fragments\ConfigurableHintBox\UI\Buttons\HideButton;
use ModulesGarden\OpenStackVpsCloud\Packages\ModuleSettings\Support\Facades\ModuleSettings;

class ConfigurableHintBox extends HintsBox implements AjaxComponentInterface, AdminAreaInterface
{
    use ToolbarTrait;

    protected bool $hideHintBox = false;
    protected bool $expandHintBox = false;

    public function preLoadHtml(): void
    {
        $burger = new DropdownMenu();
        $burger->addItem(new EditConfiguration($this->getId()));

        $this->hideHintBox = ModuleSettings::get('hideHintBox-' . $this->getId(), false);
        $this->expandHintBox = (new Container(Request::get('ajaxData', [])))->get('expand', false);

        if ($this->hideHintBox)
        {
            $button = ($this->hideHintBox && $this->expandHintBox) ?
                new HideButton($this->getId()) :
                new ExpandButton($this->getId());

            $this->addToToolbar($button);
        }

        $this->addToToolbar($burger);
    }

    public function postLoadHtml(): void
    {
        if ($this->hideHintBox && !$this->expandHintBox)
        {
            $this->setSlot('elements.hints', []);
        }
    }
}