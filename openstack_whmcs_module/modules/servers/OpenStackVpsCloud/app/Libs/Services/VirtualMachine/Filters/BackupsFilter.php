<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Filters;

class BackupsFilter extends ImageFilter
{
    public function filterIdsIn(array $ids): self
    {
        $this->data = array_filter($this->data, function($snapshot) use ($ids) {
            return in_array($snapshot['id'], $ids);
        });

        return $this;
    }
}