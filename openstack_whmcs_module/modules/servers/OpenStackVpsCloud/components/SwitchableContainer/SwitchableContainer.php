<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\SwitchableContainer;

use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;

/**
 * Container switching its elements when clicked on the whole container
 */
class SwitchableContainer extends Container
{
    public const COMPONENT = 'SwitchableContainer';

    /**
     * @param int $pointer
     * @return $this
     */
    public function setElementsPointer(int $pointer):self
    {
        $this->setSlot('pointer', $pointer);

        return $this;
    }
}
