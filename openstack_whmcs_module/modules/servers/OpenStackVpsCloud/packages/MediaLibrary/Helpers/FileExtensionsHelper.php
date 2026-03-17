<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Defaults\DefaultImagesExtensions;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Enums\Settings;

class FileExtensionsHelper
{
    public static function getAvailable(): array
    {
        $extensions = Config::get(Settings::AVAILABLE_EXTENSIONS, DefaultImagesExtensions::get());

        return is_string($extensions) ? [$extensions] : $extensions;
    }

    public static function buildSearchPatterFromExtensions(array $extensions = []): string
    {
        $parsedExtensions = array_map(function ($extension)
        {
            if ($extension == '*')
            {
                return $extension;
            }

            $parsedExtension = "";

            $extensionExploded = str_split($extension);

            foreach ($extensionExploded as $extensionElement)
            {
                $parsedExtension .= "[" . strtolower($extensionElement) . strtoupper($extensionElement) . "]";
            }

            return $parsedExtension;
        }, $extensions);

        return '{' . implode(',', $parsedExtensions) . '}';
    }

    public static function checkExtension(string $fileExtension, array $availableExtensions = []): bool
    {
        return in_array(strtolower($fileExtension), $availableExtensions) || in_array('*', $availableExtensions);
    }
}