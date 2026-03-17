<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Translations;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TranslatorTrait;

class JobStatusTranslator
{
    use TranslatorTrait;

    //TODO rework $status to enum
    public function translateStatus(string $status):string
    {
        return $this->translate($status);
    }
}