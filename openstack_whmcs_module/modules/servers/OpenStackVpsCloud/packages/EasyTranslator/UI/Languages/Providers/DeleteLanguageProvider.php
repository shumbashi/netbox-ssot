<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\Lang;

class DeleteLanguageProvider extends CrudProvider
{
    public function delete()
    {
        Lang::language($this->formData->get('originalLanguage'))->delete();
    }

}