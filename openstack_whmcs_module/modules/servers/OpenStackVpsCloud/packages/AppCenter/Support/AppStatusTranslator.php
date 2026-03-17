<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Support;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums\AppStatus;

class AppStatusTranslator
{
    use TranslatorTrait;

    public function getAvailableTranslated(): array
    {
        $translated = [];
        foreach (AppStatus::getAll() as $type) {
            $translated[$type] = $this->getTranslated($type);
        }
        return $translated;
    }

    public function getTranslated(string $type): string
    {
        return $this->translate($type);
    }
}