<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\UI\Builders;

use ModulesGarden\OpenStackVpsCloud\Components\Label\Label;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Enums\ServiceStatusConfig;

class ServiceStatusLabel
{
    public static function create(string $type): Label
    {
        $config = ServiceStatusConfig::byStatus($type);

        $label = new Label();
        $label->setText(Translator::get("service.status.{$type}"));
        $label->displayAsStatusLabel();
        $label->setType($config['type']);

        return $label;
    }

}