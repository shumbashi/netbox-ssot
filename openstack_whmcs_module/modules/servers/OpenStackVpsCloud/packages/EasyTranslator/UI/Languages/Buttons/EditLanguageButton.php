<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonEdit;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\Redirect;
use ModulesGarden\OpenStackVpsCloud\Core\Routing\Url;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Router;

class EditLanguageButton extends IconButtonEdit
{
    public function loadHtml(): void
    {
        $url = Url::route(Router::getCurrentRoute()->getName() . '@editLanguage', ['language' => ':originalLanguage']);
        $this->onClick(new Redirect($url, ['language' => ':originalLanguage']));
    }
}