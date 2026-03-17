<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Listeners;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Listener;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item;

class RemovedAllFilesFromGallery extends Listener
{
    public function handle($payload = [])
    {
        Item::query()->update(['image' => null]);
    }
}
