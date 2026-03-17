<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\TabsWidget;

use ModulesGarden\OpenStackVpsCloud\Components\Tab\Tab;
use ModulesGarden\OpenStackVpsCloud\Components\Widget\Widget;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ComponentInterface;

/**
 * Class TabsWidget
 */
class TabsWidget extends Widget
{
    public const COMPONENT = 'TabsWidget';

    /**
     * @param ComponentInterface $component
     */
    public function addTab(Tab $component)
    {
        $this->addComponent('tabs', $component);
    }

    public function disableSwiper(bool $disableSwiper = true):self
    {
        $this->setSlot('disableSwiper', $disableSwiper);

        return $this;
    }
}
