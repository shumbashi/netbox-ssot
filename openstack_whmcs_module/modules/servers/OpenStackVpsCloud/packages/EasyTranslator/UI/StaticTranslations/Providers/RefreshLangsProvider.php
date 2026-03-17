<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\LangsManager;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Providers\RefreshLanguageProvider;

class RefreshLangsProvider extends RefreshLanguageProvider
{
    public function read()
    {
        $language = Request::get('language', "");
        $service  = new LangsManager();

        $missingLangs = $service->getMissingLangs($language);
        $this->data->set('language', $language);
        $this->data->set('missingLangs', $missingLangs);
        $this->data->set('missingLangsCount', count($missingLangs));
    }
}