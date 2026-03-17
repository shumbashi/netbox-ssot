<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Filters;
class SecurityGroupsFilter extends Filter
{
    public function filterIdsIn(array $ids): self
    {
        $this->data = array_filter($this->data, function ($group) use ($ids) {
           return in_array($group['id'], $ids);
        });

        return $this;
    }
}