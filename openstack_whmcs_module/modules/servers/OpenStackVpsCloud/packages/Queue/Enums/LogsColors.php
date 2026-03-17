<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums;

class LogsColors
{
    public static function all():array
    {
        return [
            LogStatus::ERROR => [
                'type' => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::DANGER,
            ],
            LogStatus::INFO => [
                'type' => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::INFO,
            ],
            LogStatus::SUCCESS => [
                'type' => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::SUCCESS,
            ],
        ];
    }
}