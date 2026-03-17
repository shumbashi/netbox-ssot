<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services;

use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Helpers\ModuleLangs;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Helpers\WhmcsLanguages;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\Lang;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Repositories\Langs;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\Exceptions\InvalidNewLanguageName;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\Exceptions\SourceLanguageNotFound;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\Exceptions\InvalidTargetLanguage;

class LangsManager
{
    public function copyFromFileToDb($language)
    {
        Langs::saveLanguages($language, $this->getDefaultTranslations());
    }

    public function cloneLanguage($sourceLanguage, $newLanguage)
    {
        $sourceLanguage = Langs::getCombined($sourceLanguage);

        if (count($sourceLanguage) == 0)
        {
            throw new SourceLanguageNotFound();
        }

        $allWhmcsLanguages = WhmcsLanguages::getLanguages();
        $usedLanguages     = Langs::getUsedLangs();

        if (!in_array($newLanguage, $allWhmcsLanguages) || in_array($newLanguage, $usedLanguages))
        {
            throw new InvalidNewLanguageName();
        }

        Langs::saveLanguages($newLanguage, $sourceLanguage);
    }

    public function updateMissingLangs($language): void
    {
        $targetLanguage = Lang::select('language')
            ->language($language)
            ->distinct()->first();

        if (!$targetLanguage->exists)
        {
            throw new InvalidTargetLanguage();
        }

        $missingLangs = $this->getMissingLangs($language);

        Langs::saveLanguages($language, $missingLangs);
    }

    public function getMissingLangs($language): array
    {
        $defaultTranslations = $this->getDefaultTranslations();
        $existsTranslations  = Langs::getCombined($language);

        return array_diff_key($defaultTranslations, $existsTranslations);
    }

    protected function getDefaultTranslations()
    {
        $translations = ModuleLangs::getDefaultTranslations();

        if (empty($translations))
        {
            $translations = ModuleLangs::getDefaultTranslations('english');
        }

        return $translations;
    }
}