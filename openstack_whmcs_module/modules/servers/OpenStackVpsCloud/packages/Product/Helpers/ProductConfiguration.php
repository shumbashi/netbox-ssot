<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Helpers;

use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;

class ProductConfiguration
{
    public static function isSupportedInRequest(): bool
    {
        return self::isSupported(Request::get('servertype', ''));
    }

    public static function isSupported(string $type): bool
    {
        return ModuleConstants::getModuleName() === $type;
    }

    public static function isRunAsProductAddon(): bool
    {
        return basename($_SERVER["SCRIPT_NAME"]) == "configaddons.php";
    }
}
