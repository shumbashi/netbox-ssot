<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Helpers\WhmcsLanguages;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Repositories\Langs;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\LangsManager;

class CreateLanguageProvider extends CrudProvider
{
    public function read()
    {
        parent::read();

        $this->availableValues['language'] = $this->getAvailableToCreate();
    }

    protected function getAvailableToCreate(): array
    {
        $languages         = [];
        $allWhmcsLanguages = WhmcsLanguages::getLanguages();
        $usedLanguages     = Langs::getUsedLangs();

        $availableLanguages = array_diff($allWhmcsLanguages, $usedLanguages);

        foreach ($availableLanguages as $availableLanguage)
        {
            $languages[$availableLanguage] = ucfirst($availableLanguage);
        }
        return $languages;
    }

    public function create()
    {
        $service          = new LangsManager();
        $service->copyFromFileToDb($this->formData->get('language'));
    }
}