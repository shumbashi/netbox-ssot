<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\FileManager\Source;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ActionInterface;

class File extends Item
{
    protected static bool $isDir = false;
    protected static string $icon = "file-document";

    public function getClickAction(AbstractComponent $component): ?ActionInterface
    {
        return null;
    }
}