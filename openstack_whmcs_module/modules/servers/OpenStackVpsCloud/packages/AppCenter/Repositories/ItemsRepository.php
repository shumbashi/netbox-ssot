<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Repositories;

use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums\AppItemSource;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\ItemConfig;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\ItemsGroups;

class ItemsRepository
{
    public static function delete()
    {
        ItemsGroups::truncate();
        ItemConfig::truncate();
        Item::truncate();
    }

    public static function deleteLoaderItems()
    {
        Item::where('source', AppItemSource::SOURCE_LOADER)
            ->delete();
    }
}