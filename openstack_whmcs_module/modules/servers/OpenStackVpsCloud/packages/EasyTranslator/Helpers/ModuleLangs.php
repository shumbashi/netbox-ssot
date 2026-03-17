<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Helpers;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator;

class ModuleLangs
{
    public static function getDefaultTranslations(?string $locale = null): array
    {
        if ($locale)
        {
            Translator::setLocale($locale);
        }
        return Arr::dot(Translator::getCatalogue()->all()['messages']);
    }
}