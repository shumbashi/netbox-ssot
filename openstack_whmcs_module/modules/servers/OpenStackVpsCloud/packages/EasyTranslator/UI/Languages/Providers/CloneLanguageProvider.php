<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Repositories\Langs;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\LangsManager;

class CloneLanguageProvider extends CreateLanguageProvider
{
    use TranslatorTrait;

    public function read()
    {
        parent::read();

        $this->availableValues['fromLanguage'] = $this->getAvailableFromCreate();
        $this->availableValues['toLanguage']   = $this->getAvailableToCreate();
    }

    protected function getAvailableFromCreate(): array
    {
        $langsRepo     = new Langs();
        $usedLanguages = $langsRepo->getUsedLangs();
        $languages     = [];
        foreach ($usedLanguages as $usedLanguage)
        {
            $languages[$usedLanguage] = ucfirst($usedLanguage);
        }
        return $languages;
    }

    public function create()
    {
        $service = new LangsManager();
        $service->cloneLanguage($this->formData->get('fromLanguage'), $this->formData->get('toLanguage'));
    }

}