<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Helpers;

use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;

class SnapshotStatusTranslator
{
    use TranslatorTrait;

    public function getTranslated(string $status)
    {
        return $this->translate($status);
    }
}