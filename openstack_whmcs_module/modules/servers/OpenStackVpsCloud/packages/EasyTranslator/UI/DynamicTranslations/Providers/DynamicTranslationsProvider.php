<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\DynamicTranslations\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Text;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\DynamicTranslation;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\Lang;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Repositories\Langs;

class DynamicTranslationsProvider extends CrudProvider
{
    public function read()
    {
        parent::read();

        $langsRepo     = new Langs();
        $this->data->set('languages', $langsRepo->getUsedLangs());
    }

    public function create()
    {
        $regex = $this->formData->get('regex');
        $langKey = Text::toUnderscore(preg_replace("/[^a-zA-Z0-9]/", "", $regex));

        if (empty($langKey))
        {
            throw new \Exception("An error occurred while generating the key");
        }

        if (DynamicTranslation::byKey($langKey)->first()->exists)
        {
            throw new \Exception("Dynamic translation key already exists: $langKey");
        }

        DynamicTranslation::create([
            'lang'  => $langKey,
            'regex' => $regex
        ]);

        $usedLanguages = (new Langs())->getUsedLangs();

        foreach ($this->formData->toArray() as $inputName => $translation)
        {
            if (empty($translation))
            {
                continue;
            }

            if (!str_starts_with($inputName, 'translation_'))
            {
                continue;
            }

            $language = str_replace('translation_', "", $inputName);

            if (!in_array($language, $usedLanguages))
            {
                continue;
            }

            $data = [
                'language'  => $language,
                'lang'      => $langKey,
                'value'     => $translation,
                'dynamic'   => true,
            ];

            Lang::create($data);
        }
    }

    public function update()
    {
        DynamicTranslation::updateOrCreate(
            ['id'    => $this->formData->get('id')],
            ['regex' => $this->formData->get('regex')]
        );
    }

    public function delete()
    {
        $ids = explode(',', $this->formData->get('id'));

        DynamicTranslation::whereIn('id', $ids)->delete();
    }
}