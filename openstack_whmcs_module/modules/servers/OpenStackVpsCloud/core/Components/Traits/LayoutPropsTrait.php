<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Components\Traits;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\LayoutProps;

trait LayoutPropsTrait
{

    public function setLayoutProp(LayoutProps $prop):self
    {
        $this->appendCss($prop->value);

        return $this;
    }
}