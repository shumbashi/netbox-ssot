<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\Lang;

class EditLangProvider extends CrudProvider
{
    public function read()
    {
        parent::read();
        $this->data->set('value', html_entity_decode($this->data->get('value')));
        $this->data->set('originalLang', Translator::get($this->data->get('lang')));
    }

    public function update()
    {
        Lang::where('id', $this->formData->get('id'))
            ->update(['value' => html_entity_decode($this->formData->get('value'))]);
    }
}