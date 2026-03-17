<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\Factories;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Factories\AppFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\App;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\AppConfigItem;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\Factories\Parser\AppConfigParser;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item;

class AppModelFactory
{
    public static function forItem(Item $item): App
    {
        $appModel = new App();
        $appModel->fill($item->toArray());

        $app = AppFactory::factory($item->type);

        $config = [];

        foreach ($item->itemConfig()->get() as $configItem) {
            $configItemModel = new AppConfigItem();
            $configItemModel->fill($configItem->toArray());

            $config[$configItemModel->getSetting()] = $configItemModel;
        }

        $defaultConfig = $app->getDefaultConfig();

        foreach ($defaultConfig as $key => $configItem) {
            if (isset($config[$configItem->getSetting()])) {

                $config[$configItem->getSetting()]
                    ->fillMissing($configItem->toArray());

                continue;
            }

            $config[$configItem->getSetting()] = $configItem;
        }

        return $appModel->setConfig(array_values($config));
    }

    public static function forServiceItem(Service $service, Item $item, array $overrideConfig = []): App
    {
        $app = self::forItem($item);
        $app = AppConfigParser::forServiceItem($service, $app, $overrideConfig);

        return $app;
    }
}