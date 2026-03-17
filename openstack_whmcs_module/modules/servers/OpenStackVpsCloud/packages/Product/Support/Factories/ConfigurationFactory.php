<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Support\Factories;

use ModulesGarden\OpenStackVpsCloud\Core\Data\Container;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\ModuleParamsBuilder;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\Configuration\ConfigurationContainer;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration;

class ConfigurationFactory
{
    public static function fromProduct(int $productId):ConfigurationContainer
    {
        $productConfiguration = new ProductConfiguration($productId);

        return (new ConfigurationContainer())
            ->setProductSetting($productConfiguration->get());
    }

    public static function fromService(int $serviceId): ConfigurationContainer
    {
        return self::fromParams((new ModuleParamsBuilder())->get($serviceId));
    }

    public static function fromParams(array $params): ConfigurationContainer
    {
        $paramsContainer = new Container($params);

        $configurationContainer = new ConfigurationContainer();

        if ($serviceId = $paramsContainer->get('serviceid'))
        {
            $configurationContainer->setServiceId($serviceId);
        }

        if ($productId = $paramsContainer->get('packageid'))
        {
            $configurationContainer->setProductSetting((new ProductConfiguration($productId))->get());
        }

        return $configurationContainer
            ->setCustomFields($paramsContainer->get('customfields', []))
            ->setConfigurableOptions($paramsContainer->get('configoptions', []));
    }
}