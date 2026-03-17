<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Others;

use ModulesGarden\OpenStackVpsCloud\Components\Label\Label;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Translations\LogsTypeTranslator;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Enums\Colors;

class TypeLabel
{
    public static function create($type)
    {
        $config = Colors::all()[$type];

        $label = new Label();
        $label->setText((new LogsTypeTranslator())->translateType($type));
        $label->displayAsStatusLabel();
        $config['type'] ? $label->setType($config['type']) : null;
        $config['textColor'] ? $label->setTextColor($config['textColor']) : null;

        return $label;
    }
}
