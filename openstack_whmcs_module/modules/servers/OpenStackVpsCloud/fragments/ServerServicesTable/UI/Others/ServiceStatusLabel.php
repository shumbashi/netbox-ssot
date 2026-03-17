<?php

namespace ModulesGarden\OpenStackVpsCloud\Fragments\ServerServicesTable\UI\Others;

use ModulesGarden\OpenStackVpsCloud\Components\Label\Label;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Enums\HostingDomainStatus;

class ServiceStatusLabel
{
    public const COLORS = [
        HostingDomainStatus::STATUS_PENDING => [
            'type'   => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::INFO,
            'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonInfo::class,
            'icon'   => 'hourglass-alt',
        ],
        HostingDomainStatus::STATUS_ACTIVE => [
            'type'   => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::SUCCESS,
            'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonSuccess::class,
            'icon'   => 'check',
        ],
        HostingDomainStatus::STATUS_SUSPENDED => [
            'type'   => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::WARNING,
            'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonWarning::class,
            'icon'   => 'play',
        ],
        HostingDomainStatus::STATUS_TERMINATED => [
            'type'   => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::DANGER,
            'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonDanger::class,
            'icon'   => 'minus-circle-outline',
        ],
        HostingDomainStatus::STATUS_CANCELLED => [
            'type'   => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::DEFAULT,
            'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonBasic::class,
            'icon'   => 'minus-circle-outline',
        ],
        HostingDomainStatus::STATUS_FRAUD => [
            'type'   => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::DANGER,
            'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonDanger::class,
            'icon'   => 'minus-circle-outline',

        ],
        HostingDomainStatus::STATUS_COMPLETED => [
            'type'   => \ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Type::INFO,
            'button' => \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonInfo::class,
            'icon'   => 'hourglass-alt',
        ],
    ];

    public static function create($type) : Label
    {
        $config = self::COLORS[$type];

        $label = new Label();
        $label->setText(Translator::get("service.status.{$type}"));
        $label->displayAsStatusLabel();
        $label->setType($config['type']);

        return $label;
    }
}