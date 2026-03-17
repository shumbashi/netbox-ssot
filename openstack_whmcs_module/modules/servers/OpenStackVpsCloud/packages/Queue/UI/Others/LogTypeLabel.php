<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Others;

use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\LogsColors;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Translations\LogStatusTranslator;

class LogTypeLabel extends TypeLabel
{
    protected function getConfig(string $type): array
    {
        return LogsColors::all()[$type];
    }

    protected function translateStatus(string $type): string
    {
        return (new LogStatusTranslator())->translateStatus($type);
    }
}