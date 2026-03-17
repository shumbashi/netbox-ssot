<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Config\Endpoint\Services;

use ModulesGarden\OpenStackVpsCloud\Packages\Product\Support\Factories\ConfigurationFactory;

class RegionResolver
{
    private const SETTING_REGION = 'region';

    public function resolve(int $serviceId, int $productId): ?string
    {
        if ($serviceId > 0) {
            try {
                if ($region = ConfigurationFactory::fromService($serviceId)->get(self::SETTING_REGION)) {
                    return $region;
                }
            } catch (\Exception $e) {
            }
        }
        
        if ($productId > 0) {
            try {
                if ($region = ConfigurationFactory::fromProduct($productId)->get(self::SETTING_REGION)) {
                    return $region;
                }
            } catch (\Exception $e) {
            }
        }

        return null;
    }
}

