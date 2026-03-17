<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Decorators;


class VmDecorator
{
    const VPS = 'VPS#';

    public static function nameDecorate(int $serviceId)
    {
        return self::VPS . $serviceId;
    }
}