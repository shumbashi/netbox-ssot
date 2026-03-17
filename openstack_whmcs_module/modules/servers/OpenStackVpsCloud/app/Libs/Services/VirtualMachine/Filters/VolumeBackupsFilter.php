<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Filters;
class VolumeBackupsFilter extends Filter
{
    const DESCRIPTION_PREFIX   = 'WHMCS_SERVICE_';

    public function filterByServiceId(int|string $serviceId): self
    {
        $this->data = array_filter($this->data, function ($snapshot) use ($serviceId) {
            if (!isset($snapshot['description'])) {
                return false;
            }

            if (!str_starts_with($snapshot['description'], self::DESCRIPTION_PREFIX)) {
                return false;
            }

            $snapshotServiceId = str_replace(self::DESCRIPTION_PREFIX, '', $snapshot['description']);
            return $snapshotServiceId == $serviceId;
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

    public function filterIdsNotIn(array $ids): self
    {
        $this->data = array_filter($this->data, function($snapshot) use ($ids) {
            return !in_array($snapshot['id'], $ids);
        });

        return $this;
    }

    public function getOldest()
    {
        if (empty($this->data))
        {
            return null;
        }

        $oldest = reset($this->data);
        foreach ($this->data as $snapshot)
        {
            if (strtotime($snapshot['created']) < strtotime($oldest['created']))
            {
                $oldest = $snapshot;
            }
        }

        return $oldest;
    }
}


