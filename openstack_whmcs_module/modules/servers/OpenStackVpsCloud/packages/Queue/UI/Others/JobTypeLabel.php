<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Others;

use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Colors;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Translations\JobStatusTranslator;

class JobTypeLabel extends TypeLabel
{
    protected function getConfig(string $type): array
    {
        return Colors::all()[$type];
    }

    protected function translateStatus(string $type): string
    {
        return (new JobStatusTranslator())->translateStatus($type);
    }
}