<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\Enums;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type;

class Colors
{
    public const TOTAL_KEY = 'total';

    public static function all():array
    {
        return [
            LogTypes::EMERGENCY => [
                'type'   => Type::DANGER,
                'icon'   => 'alert',
                'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonDanger::class,
            ],
            LogTypes::ALERT     => [
                'type'   => Type::PRIMARY,
                'icon'   => 'alert-circle-o',
                'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonDanger::class,
            ],
            LogTypes::CRITICAL  => [
                'type'   => Type::CRITICAL,
                'icon'   => 'alert-decagram',
                'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonDanger::class,
            ],
            LogTypes::ERROR     => [
                'type'   => Type::DANGER,
                'icon'   => 'alert-octagon',
                'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonDanger::class,
            ],
            LogTypes::WARNING   => [
                'type'   => Type::WARNING,
                'icon'   => 'alert-circle',
                'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonWarning::class,
            ],
            LogTypes::NOTICE    => [
                'type'   => Type::INFO,
                'icon'   => 'information',
                'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonInfo::class,
            ],
            LogTypes::INFO      => [
                'type'   => Type::INFO,
                'icon'   => 'information-outline',
                'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonInfo::class,
            ],
            LogTypes::DEBUG     => [
                'type'   => Type::DEFAULT,
                'icon'   => 'bug',
                'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonBasic::class,
            ],
            self::TOTAL_KEY     => [
                'type'   => Type::DEFAULT,
                'icon'   => 'layers',
                'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonClose::class,
            ],
        ];
    }
}