<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Defaults\DefaultLibraryPath;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Enums\Settings;

class LibraryPathHelper
{
    public static function getPath(): string
    {
        return Config::get(Settings::MEDIA_LIBRARY_PATH, DefaultLibraryPath::getDefaultPath());
    }

    public static function getFilePath(string $fileName)
    {
        return self::getPath() . '/' .$fileName;
    }
}