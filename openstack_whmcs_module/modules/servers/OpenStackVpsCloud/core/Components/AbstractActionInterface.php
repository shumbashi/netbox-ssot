<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Components;

use JsonSerializable;

class AbstractActionInterface implements JsonSerializable, \ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ActionInterface
{
    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [];
    }
}
