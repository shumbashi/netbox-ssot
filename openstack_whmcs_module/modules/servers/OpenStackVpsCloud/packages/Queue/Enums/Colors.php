<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums;

class Colors
{
    public const TOTAL_KEY = 'total';

    public static function all():array
    {
        return [
            Status::ERROR => [
                'type'   => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::DANGER,
                'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonDanger::class,
                'icon'   => 'close',
                ],
            Status::FAILED => [
                'type'   => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::CRITICAL,
                'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonCritical::class,
                'icon'   => 'minus-circle-outline',
            ],
            Status::FINISHED => [
                'type'   => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::SUCCESS,
                'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonSuccess::class,
                'icon'   => 'check',
            ],
            Status::RUNNING => [
                'type'   => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::WARNING,
                'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonWarning::class,
                'icon'   => 'play',
            ],
            Status::WAITING => [
                'type'   => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::INFO,
                'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonInfo::class,
                'icon'   => 'clock-outline',
            ],
            Status::PENDING => [
                'type'   => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::PRIMARY,
                'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonPrimary::class,
                'icon'   => 'timer-sand',
            ],
            Status::CANCELLED => [
                'type'   => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::DEFAULT,
                'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonBasic::class,
                'icon'   => 'minus-circle-outline',
            ],
            self::TOTAL_KEY => [
                'type'   => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::DEFAULT,
                'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonClose::class,
                'icon'   => 'layers',
            ],
        ];
    }
}
