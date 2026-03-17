<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Components\Traits;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ActionInterface;

trait ActionOnClickTrait
{
    use ActionsTrait;

    public function onClick(ActionInterface $action): self
    {
        $this->addAction('onClick', $action);

        return $this;
    }
}