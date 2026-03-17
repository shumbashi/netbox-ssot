<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Repositories;

use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\Lang;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\Lang as LangModel;

class Langs
{
    public static function getUsedLangs(): array
    {
        return Lang::select('language')->distinct()->pluck('language')->toArray();
    }

    public static function getCombined($language): array
    {
        return Lang::language($language)->pluck('value', 'lang')->toArray();
    }

    public static function saveLanguages($language, array $langs)
    {
        $data = [];

        foreach ($langs as $key => $value)
        {
            $data[] = ['language' => $language, 'lang' => $key, 'value' => $value];
        }

        LangModel::insert($data);
    }
}