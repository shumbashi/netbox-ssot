<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Config;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Builders\ServiceUrlBuilder;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Config\Endpoint\EndpointResolver;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Config\Endpoint\Services\RegionResolver;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Factories\ServiceFactory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration;

class ConfigResolver
{
    /*Do not touch this*/
    public static function resolveConfig(array $config)
    {
        $interfaceClass = ServiceFactory::getClass($config['interface']);
        $endpoint = $interfaceClass::USEENDPOINT;
        $version = $interfaceClass::VERSION;

        if (!self::catalogHasType($config['catalog'], $endpoint)) {
            throw new OSException("Endpoint '$endpoint' is unavailable. Please check server configuration.", 5);
        }

        $endpointsList = self::flattenCatalog($config['catalog']);

        $regions = array_values(array_unique(array_column($endpointsList, 'region')));

        $regionResolver = new RegionResolver();
        $region = $regionResolver->resolve($config['serviceId'] ?? 0, $config['productId'] ?? 0) 
            ?? $regions[0] ?? null;

        $endpointData = (new EndpointResolver($config['serverId'], $endpoint, $endpointsList))
            ->setDesiredInterface($config['endpointType'])
            ->setRegion($region)
            ->resolve($config['params']['nodeID']);

        if (!$endpointData || !isset($endpointData['url'])) {
            throw new OSException("Unable to resolve endpoint for service '$endpoint' in region '$region'. Please check server configuration.", 5);
        }

        $serviceUrlModel = (new ServiceUrlBuilder($endpointData['url']))
            ->setRegions($config['allRegions'])
            ->setRegion($config['usedRegion'])
            ->setTenantId($config['tenantId'])
            ->setVersion($version);

        $debugEnabled = false;
        if ($config['productId']) {
            $debugEnabled = (bool)(new ProductConfiguration($config['productId']))->get()['debug_mode'];
        }

        return [
            $serviceUrlModel->getUrl(),
            $config['token'],
            $config['params'],
            $config['certificate'],
            $debugEnabled
        ];
    }
    private static function catalogHasType(?array $catalog, string $type)
    {
        if (!$catalog) {
            return false;
        }
        
        foreach ($catalog as $catalogItem)
        {
            if (isset($catalogItem['type']) && $catalogItem['type'] == $type)
            {
                return true;
            }
        }
        return false;
    }

    private static function flattenCatalog(?array $catalog)
    {
        $flattened = [];

        if (!$catalog) {
            return $flattened;
        }

        foreach ($catalog as $value) {
            if (!isset($value['endpoints']) || !is_array($value['endpoints'])) {
                continue;
            }
            
            foreach ($value['endpoints'] as $endpoint) {
                if (!isset($value['type'])) {
                    continue;
                }
                
                $flattenedEndpoint = [
                    'service' => ucfirst($value['type']),
                ];

                $flattened[] = array_merge($flattenedEndpoint, $endpoint);
            }
        }

        return $flattened;
    }
}