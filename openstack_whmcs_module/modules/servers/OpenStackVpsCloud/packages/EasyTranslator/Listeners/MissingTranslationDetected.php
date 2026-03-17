<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Listeners;

use ModulesGarden\OpenStackVpsCloud\Core\Events\Listener;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Libs\DynamicTranslations\Helpers\DynamicTranslationHelper;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Loader\DynamicTranslations;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\MissingLang;
use \ModulesGarden\OpenStackVpsCloud\Core\Events\Events\MissingTranslationDetected as EventsMissingTranslationDetected;

class MissingTranslationDetected extends Listener
{
    public function handle($payload = [])
    {
        if (!$payload instanceof EventsMissingTranslationDetected)
        {
            return null;
        }

        if (empty($payload->getLang()))
        {
            throw new \Exception("Translation key cannot be empty");
        }

        if (empty($payload->getLocale()))
        {
            throw new \Exception("Language cannot be empty");
        }

        if ($translationData = DynamicTranslationHelper::find($payload->getCatalog()->all(DynamicTranslations::DYNAMICS_DOMAIN), $payload->getLang()))
        {
            $dynamicKey = $translationData->getKey();

            $result = Translator::get($dynamicKey, $translationData->getReplacements($payload->getReplacements()), $payload->getLocale());

            if ($dynamicKey == $result)
            {
                $this->reportMissingTranslation($payload->getLocale(), $dynamicKey, $payload->getSourceText());
            }

            return $result;
        }

        $this->reportMissingTranslation($payload->getLocale(), $payload->getLang(), $payload->getSourceText());

        return null;
    }

    protected function reportMissingTranslation(string $language, string $lang, string $sourceText):void
    {
        if (!Config::get('easyTranslator.missingLangsSupport', true))
        {
            return;
        }

        MissingLang::updateOrCreate([
            'language'  => $language,
            'lang'      => $lang,
            'source'    => $sourceText
        ], []);
    }
}