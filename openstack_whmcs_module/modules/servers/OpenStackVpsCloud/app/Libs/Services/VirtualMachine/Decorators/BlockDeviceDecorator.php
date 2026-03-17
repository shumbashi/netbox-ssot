<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Decorators;


class BlockDeviceDecorator
{
    public static function nameDecorator($letter)
    {
        return 'vd' . $letter;
    }
}