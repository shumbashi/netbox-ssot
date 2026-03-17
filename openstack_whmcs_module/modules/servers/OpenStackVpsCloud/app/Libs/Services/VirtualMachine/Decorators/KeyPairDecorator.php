<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Decorators;


class KeyPairDecorator
{
    const KEY  = 'Key';
    const HASH = 'Hash';

    public static function nameDecorator($serviceID)
    {
        return self::KEY . $serviceID . self::HASH . md5(time());
    }
}