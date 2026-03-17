<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\Lang;

class MassDeleteProvider extends CrudProvider
{
    public function delete()
    {
        $ids = explode(',', $this->formData->get('id'));
        Lang::whereIn('language', $ids)->delete();
    }

}