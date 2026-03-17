<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\Factories;

use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Factories\AppFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\AppConfigItem;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\ItemConfig;

class AppConfigItemFactory
{
    public static function forItem(ItemConfig $item): AppConfigItem
    {
        $app = AppFactory::factory($item->appItem->type);

        $configItemModel = new AppConfigItem();
        $configItemModel->fill($item->toArray());

        foreach ($app->getDefaultConfig() as $defaultModel) {
            if ($defaultModel->getSetting() !== $configItemModel->getSetting()) {
                continue;
            }

            return $configItemModel->fillMissing($defaultModel->toArray());
        }

        return $configItemModel;
    }

    public static function forItemId(int $itemId)
    {
        $item = ItemConfig::findOrFail($itemId);

        return self::forItem($item);
    }
}