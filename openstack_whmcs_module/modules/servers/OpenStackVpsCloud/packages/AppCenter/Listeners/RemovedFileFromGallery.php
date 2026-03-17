<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Listeners;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Listener;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item;

class RemovedFileFromGallery extends Listener
{
    public function handle($payload = [])
    {
        Item::where('image', $payload->name)
            ->update(['image' => null]);
    }
}
