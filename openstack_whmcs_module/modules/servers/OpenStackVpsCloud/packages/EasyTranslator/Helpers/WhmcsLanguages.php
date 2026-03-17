<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Helpers;

use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;

class WhmcsLanguages
{
    public static function getLanguages(): array
    {
        $glob              = glob(ModuleConstants::getFullPathWhmcs('lang') . DIRECTORY_SEPARATOR . "*.php");

        $languages = [];

        foreach ($glob as $fileName)
        {
            $fileName = pathinfo($fileName)['filename'];
            if ($fileName == 'index')
            {
                continue;
            }
            $languages[] = $fileName;
        }

        return $languages;
    }
}