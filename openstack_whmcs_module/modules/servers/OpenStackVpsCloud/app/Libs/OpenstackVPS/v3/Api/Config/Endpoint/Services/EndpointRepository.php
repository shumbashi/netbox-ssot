<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Config\Endpoint\Services;

use ModulesGarden\OpenStackVpsCloud\App\Models\Servers;

class EndpointRepository
{
    public function getByServerId(int $serverId): ?array
    {
        if ($serverId <= 0) {
            return null;
        }

        $record = Servers::byServerID($serverId)
            ->byService(Servers::AVAILABLE_ENDPOINTS)
            ->first();
            
        if (!$record || !$record->endpoint) {
            return null;
        }
        
        $unserialized = @unserialize($record->endpoint);
        if ($unserialized === false && $record->endpoint !== serialize(false)) {
            return null;
        }
        
        return is_array($unserialized) ? $unserialized : null;
    }

    public function store(int $serverId, array $endpoints): void
    {
        $model = new Servers();
        $model->createTableIfNotExist();
        $model->createOrUpdate($serverId, Servers::AVAILABLE_ENDPOINTS, null, serialize($endpoints));
    }

    public function storeEmpty(int $serverId): void
    {
        $this->store($serverId, []);
    }
}

