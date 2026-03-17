<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\MissingLangs\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\Lang;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\MissingLang;

class SetTranslationProvider extends CrudProvider
{
    public function read()
    {
        parent::read();

        $missingLang = MissingLang::findOrFail($this->data->get('id'));
        $sourceTranslation = Translator::get($missingLang->lang, [], 'english');

        $this->data->set('lang', html_entity_decode($missingLang->lang));
        $this->data->set('sourceText', $this->getSourceText($missingLang));
        $this->data->set('hasSourceTranslation', $this->hasSourceTranslation($missingLang, $sourceTranslation));
        $this->data->set('sourceTranslation', $sourceTranslation);
        $this->data->set('langExists', Lang::where(['language' => $missingLang->language, 'lang' => $missingLang->lang])->first()->exists);
    }

    public function update()
    {
        $missingLang = MissingLang::findOrFail($this->formData->get('id'));

        Lang::updateOrCreate(
            ['language' => $missingLang->language, 'lang' => $missingLang->lang],
            ['value' => $this->formData->get('translation')]
        );

        $missingLang->delete();
    }

    protected function getSourceText(MissingLang $missingLang):string
    {
        return $missingLang->source ?: Arr::last((array)explode('.', $missingLang->lang));
    }

    protected function hasSourceTranslation(MissingLang $missingLang, string $sourceTranslation):bool
    {
        return $missingLang->language != 'english' && $sourceTranslation != $missingLang->lang;
    }
}