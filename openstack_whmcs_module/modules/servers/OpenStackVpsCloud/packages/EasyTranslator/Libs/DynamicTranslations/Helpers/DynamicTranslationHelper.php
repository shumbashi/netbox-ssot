<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Libs\DynamicTranslations\Helpers;

use ModulesGarden\OpenStackVpsCloud\Core\Translation\Models\TranslationData;

class DynamicTranslationHelper
{
    public static function find(array $regexps, string $key): ?TranslationData
    {
        foreach ($regexps as $langKey => $regex)
        {
            if (preg_match('/' . $regex . '/', $key, $matches))
            {
                $response = new TranslationData($langKey);

                unset($matches[0]);

                foreach ($matches as $keyIndex => $match)
                {
                    $response->addReplacement("$$keyIndex", $match);
                }

                return $response;
            }
        }

        return null;
    }
}