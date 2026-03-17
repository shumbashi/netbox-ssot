<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Filters\SecurityGroupsFilter;
use ModulesGarden\OpenStackVpsCloud\App\Traits\ApiTrait;

class SecurityGroupManager
{
    use ApiTrait;
    protected ?string $vmId;
    protected ?string $tenantId;

    public function __construct(?string $vmId, ?string $tenantId)
    {
        $this->vmId = $vmId;
        $this->tenantId = $tenantId;
    }

    public function change(array $new): void
    {
        $existing = $this->api->network()->listSecurityGroups([
            'project_id' => $this->tenantId
        ]);

        $new = (new SecurityGroupsFilter($existing))
            ->filterIdsIn($new)
            ->get();

        $current = $this->getGroups();

        $remove = array_udiff($current, $new, function($a, $b) {
            return $a['id'] <=> $b['id'];
        });

        $assign = array_udiff($new, $current, function($a, $b) {
            return $a['id'] <=> $b['id'];
        });

        foreach ($remove as $group)
        {
            try {
                $this->api->compute()->unassignSecurityGroupVPS($this->vmId, $group['name']);
            } catch (\Throwable $t) {
            }
        }

        foreach ($assign as $group) {
            try {
                $this->api->compute()->assignSecurityGroupVPS($this->vmId, $group['name']);
            } catch (\Throwable $t) {
            }
        }
    }

    public function getGroups(): ?array
    {
        $list = $this->api->compute()->getSecurityGroupList($this->vmId);
        return $list['security_groups'];
    }
}