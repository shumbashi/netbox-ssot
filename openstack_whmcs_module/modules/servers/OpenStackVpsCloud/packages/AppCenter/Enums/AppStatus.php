<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums;

class AppStatus
{
    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLED = 'disabled';

    public static function getAll()
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_DISABLED
        ];
    }
}