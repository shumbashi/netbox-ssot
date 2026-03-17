<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Defaults;

class DefaultImagesExtensions
{
    public static function get(): array
    {
        return ['jpeg', 'jpg', 'png', 'gif', 'svg', 'webp'];
    }
}