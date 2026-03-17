<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Components\Decorator;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\DisplayTypes;

class ChildrenSize extends AbstractDecorator
{
    public function fitToParent(): self
    {
        return $this->appendClass(DisplayTypes::FLEX);
    }
}