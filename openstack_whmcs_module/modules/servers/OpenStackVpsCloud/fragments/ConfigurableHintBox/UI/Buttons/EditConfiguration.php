<?php

namespace ModulesGarden\OpenStackVpsCloud\Fragments\ConfigurableHintBox\UI\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\DropdownMenuItem\DropdownMenuItem;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Fragments\ConfigurableHintBox\UI\Modals\EditConfigurationModal;

class EditConfiguration extends DropdownMenuItem implements AdminAreaInterface
{
    protected string $widgetId;

    public function __construct(string $widgetId)
    {
        parent::__construct();

        $this->widgetId = $widgetId;
    }

    public function loadHtml(): void
    {
        $this->setIcon('cog');
        $this->onClick(Action::modalLoad(new EditConfigurationModal())->withParams(["widgetId" => $this->widgetId ]));
    }
}