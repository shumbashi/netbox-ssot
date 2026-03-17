<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Filters;
class SnapshotsFilter extends Filter
{
    const WHMCS_HOSTING_ID = 'whmcs-hosting-id';

    public function filterByServiceId(int|string $serviceId): self
    {
        $this->data = array_filter($this->data, function($snapshot) use ($serviceId) {
            return $snapshot['metadata'][self::WHMCS_HOSTING_ID] == $serviceId;
        });

        return $this;
    }

    public function filterIdsNotIn(array $ids): self
    {
        $this->data = array_filter($this->data, function($snapshot) use ($ids) {
            return !in_array($snapshot['id'], $ids);
        });

        return $this;
    }

    public function filterByVolumeId($volumeId): self
    {
        $this->data = array_filter($this->data, function($snapshot) use ($volumeId) {
            return $snapshot['volumeID'] == $volumeId;
        });

        return $this;
    }

    public function filterIdsIn(array $ids): self
    {
        $this->data = array_filter($this->data, function($snapshot) use ($ids) {
            return in_array($snapshot['id'], $ids);
        });

        return $this;
    }
}