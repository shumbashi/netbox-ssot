<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\LayoutWithSidebar;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ComponentsContainerTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\CssContainerTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\SizeTrait;

class LayoutWithSidebar extends AbstractComponent
{
    use SizeTrait;
    use CssContainerTrait;
    use ComponentsContainerTrait;

    public const COMPONENT = 'LayoutWithSidebar';

    public function addSidebar($sidebar): self
    {
        $this->addComponent('sidebars', $sidebar);

        return $this;
    }

    public function clearSidebars(): self
    {
        $this->setSlot('sidebars', []);

        return $this;
    }

}