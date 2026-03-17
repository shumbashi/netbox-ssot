<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Managers;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Config\Endpoint\Services\EndpointNormalizer;

class EndpointsManager
{
    protected $endpointsList;

    public function __construct(array $endpointsList)
    {
        $this->endpointsList = $endpointsList;
    }

    public function byEndpointType($endpointType = null)
    {
        if (!$endpointType)
        {
            $this->endpointsList = [];

            return $this;
        }

        $endpoints = [];

        foreach ($this->endpointsList as $endpoint)
        {
            if ($endpoint['interface'] === $endpointType)
            {
                $endpoints[] = $endpoint;
            }
        }

        $this->endpointsList = $endpoints;

        return $this;
    }

    public function byRegion(?string $region)
    {
        $endpoints = [];

        foreach ($this->endpointsList as $endpoint)
        {
            if ($endpoint['region'] === $region)
            {
                $endpoints[] = $endpoint;
            }
        }

        $this->endpointsList = $endpoints;

        return $this;
    }


    public function byService(string $service)
    {
        $endpoints = [];
        $normalizer = new EndpointNormalizer();
        
        foreach ($this->endpointsList as $endpoint)
        {
            if ($normalizer->shouldFilterEndpoint($endpoint['service'] ?? ''))
            {
                continue;
            }

            if ($endpoint['service'] === $service)
            {
                $endpoints[] = $endpoint;
            }
        }

        $this->endpointsList = $endpoints;

        return $this;
    }

    public function getByNodeId(string $nodeID)
    {
        foreach ($this->endpointsList as $endpoint)
        {
            if ($endpoint['id'] == $nodeID)
            {
                $this->endpointsList = [$endpoint];

                return $this;
            }
        }

        return $this;
    }

    public function getEndpoints()
    {
        return $this->endpointsList;
    }

    public function first()
    {
        return $this->endpointsList[array_key_first($this->endpointsList)];
    }
}