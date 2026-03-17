<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Helpers\Translators;

use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;

class ConnectionErrorTranslator
{
    use TranslatorTrait;

    public  function getTranslated(string $lang): string
    {
        return $this->translate($lang);
    }
}