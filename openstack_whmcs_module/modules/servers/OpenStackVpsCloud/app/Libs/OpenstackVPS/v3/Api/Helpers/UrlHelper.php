<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Helpers;

class UrlHelper
{
    public static function getDomain(string $url, $version)
    {
        $removePrefix = [];
        preg_match("/(.*)[\:][\/][\/]/", $url, $removePrefix);
        if ($version) {
            $url = str_replace($version, '', $url);
        }

        return str_replace($removePrefix, '', $url);
    }

}