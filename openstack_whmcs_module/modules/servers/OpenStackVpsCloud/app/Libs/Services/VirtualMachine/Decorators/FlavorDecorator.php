<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Decorators;

class FlavorDecorator
{
    const CUSTOM_FLAVOR = 'CustomFlavor';
    const FLAVOR        = 'flavor';

    public static function nameDecorator(string $domain)
    {
        return self::CUSTOM_FLAVOR . $domain . rand(1, 10000);
    }
}