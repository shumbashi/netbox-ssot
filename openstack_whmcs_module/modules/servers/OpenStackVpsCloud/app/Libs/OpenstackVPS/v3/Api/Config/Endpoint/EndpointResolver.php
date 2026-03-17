<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Config\Endpoint;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\EndpointsManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Config\Endpoint\Services\EndpointNormalizer;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Config\Endpoint\Services\EndpointRepository;
use ModulesGarden\OpenStackVpsCloud\App\Models\Servers;

class EndpointResolver
{
    private const DEFAULT_INTERFACE = 'public';

    private EndpointRepository $repository;
    private EndpointNormalizer $normalizer;

    private int $serverID;
    private string $endpoint;
    private array $endpointCatalog;
    private string $desiredInterface;
    private ?string $region;

    public function __construct(
        int $serverID,
        string $endpoint,
        array $endpointCatalog
    ) {
        $this->normalizer = new EndpointNormalizer();
        $this->repository = new EndpointRepository();
        $this->serverID = $serverID;
        $this->endpoint = $endpoint;
        $this->endpointCatalog = $endpointCatalog;
        $this->desiredInterface = self::DEFAULT_INTERFACE;
        $this->region = null;
    }

    public function setDesiredInterface(string $interface): self
    {
        $this->desiredInterface = $interface;
        return $this;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;
        return $this;
    }

    public function resolve(?string $desiredNodeId = null): ?array
    {
        $dbEndpoints = $this->repository->getByServerId($this->serverID) ?? [];
        
        if ($desiredNodeId) {
            return $this->query($dbEndpoints, $desiredNodeId)->first() 
                ?: $this->query($this->endpointCatalog, $desiredNodeId)->first();
        }

        if ($endpoint = $this->query($dbEndpoints)->first()) {
            return $endpoint;
        }

        if (empty($dbEndpoints)) {
            $this->storeFromCatalog();
            if ($endpoint = $this->query($this->repository->getByServerId($this->serverID) ?? [])->first()) {
                return $endpoint;
            }
        }

        $endpoint = $this->query($this->endpointCatalog)->first();
        if ($endpoint) {
            $this->updateCache($endpoint);
        }

        return $endpoint;
    }

    private function query(array $endpoints, ?string $nodeId = null): EndpointsManager
    {
        $manager = (new EndpointsManager($endpoints))
            ->byService($this->normalizer->normalizeServiceName(ucfirst($this->endpoint)));
        
        if ($nodeId) {
            return $manager->getByNodeId($nodeId);
        }
        
        if ($this->region) {
            $manager = $manager->byRegion($this->region);
        }
        
        return $manager->byEndpointType($this->desiredInterface);
    }

    private function storeFromCatalog(): void
    {
        if (empty($this->endpointCatalog)) {
            return;
        }

        $normalized = $this->normalizer->normalizeAndFilter($this->endpointCatalog);
        $formatted = array_map(fn($e) => [
            'id' => $e['id'] ?? '',
            'service' => $e['service'] ?? '',
            'region' => $e['region'] ?? '',
            'interface' => $e['interface'] ?? self::DEFAULT_INTERFACE,
            'url' => $e['url'] ?? ''
        ], $normalized);

        $this->repository->store($this->serverID, $formatted);
    }

    private function updateCache(array $endpoint): void
    {
        $model = new Servers();
        $model->createTableIfNotExist();
        $model->createOrUpdate(
            $this->serverID,
            $endpoint['interface'] ?? self::DEFAULT_INTERFACE,
            $endpoint['id'] ?? '',
            $endpoint['url'] ?? ''
        );
    }
}