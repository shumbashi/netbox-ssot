<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Components\Traits;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ActionInterface;

trait ActionOnCloseTrait
{
    use ActionsTrait;

    public function onClose(ActionInterface $action): self
    {
        $this->addAction('onClose', $action);

        return $this;
    }
}