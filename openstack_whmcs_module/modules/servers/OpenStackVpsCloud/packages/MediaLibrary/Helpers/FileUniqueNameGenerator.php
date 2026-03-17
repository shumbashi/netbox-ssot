<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers;

class FileUniqueNameGenerator
{
    public static function generateUniqueFileName($originalFileName, $extension)
    {
        return uniqid($originalFileName . '-') . '.' . $extension;
    }
}