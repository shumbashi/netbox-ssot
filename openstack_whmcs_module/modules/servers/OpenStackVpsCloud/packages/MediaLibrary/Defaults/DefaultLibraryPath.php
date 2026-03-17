<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Defaults;

use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;

class DefaultLibraryPath
{
    public static function getDefaultPath(): string
    {
        return ModuleConstants::getFullPath('storage', 'gallery');
    }
}
