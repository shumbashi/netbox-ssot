<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers;

use ModulesGarden\OpenStackVpsCloud\Core\Helper\BuildUrl;

class ContentUrlGenerator
{
    public static function generateWithParams($params = [])
    {
        if (!isset($params['fileName']))
        {
            return null;
        }

        if (!file_exists(LibraryPathHelper::getFilePath($params['fileName'])))
        {
            return null;
        }

        return BuildUrl::getPackagesURL('MediaLibrary', 'Support', 'content.php') . '?' . http_build_query($params);
    }
}