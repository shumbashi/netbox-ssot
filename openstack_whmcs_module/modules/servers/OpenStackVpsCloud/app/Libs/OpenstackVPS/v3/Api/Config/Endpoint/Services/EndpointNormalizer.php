<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Config\Endpoint\Services;

use ModulesGarden\OpenStackVpsCloud\App\Models\Servers;

class EndpointNormalizer
{
    public function normalizeServiceName(string $service): string
    {
        return $service === ucfirst(Servers::VOLUME) ? ucfirst(Servers::VOLUME_V3) : $service;
    }

    public function shouldFilterEndpoint(string $service): bool
    {
        $volumeService = ucfirst(Servers::VOLUME);
        return strpos($service, $volumeService) !== false && strpos($service, 'v3') === false;
    }

    public function normalizeAndFilter(array $endpoints): array
    {
        $normalized = [];
        
        foreach ($endpoints as $endpoint) {
            if (!is_array($endpoint)) {
                continue;
            }

            $service = $this->normalizeServiceName($endpoint['service'] ?? '');
            
            if ($this->shouldFilterEndpoint($service)) {
                continue;
            }
            
            $endpoint['service'] = $service;
            $normalized[] = $endpoint;
        }
        
        return array_values($normalized);
    }
}

