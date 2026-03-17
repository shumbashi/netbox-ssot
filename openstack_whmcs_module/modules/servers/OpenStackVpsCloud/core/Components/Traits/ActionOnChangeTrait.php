<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Components\Traits;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ActionInterface;

trait ActionOnChangeTrait
{
    use ActionsTrait;

    public function onChange(ActionInterface $action): self
    {
        $this->addAction('onChange', $action);

        return $this;
    }
}