<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Repositories;

use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\DynamicTranslation;

class DynamicTranslations
{
    public static function getCombined(): array
    {
        return DynamicTranslation::pluck('regex', 'lang')->toArray();
    }
}