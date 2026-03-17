<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Translations\Widgets;

use ModulesGarden\OpenStackVpsCloud\Components\TabsWidget\TabsWidget;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Translations\Tabs\DynamicTranslationsTab;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Translations\Tabs\LanguagesTab;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Translations\Tabs\MissingLangsTab;

class LanguagesTabsWidget extends TabsWidget
{
    public function loadHtml():void
    {
        $this->addTab(new LanguagesTab());
        $this->addTab(new MissingLangsTab());
        $this->addTab(new DynamicTranslationsTab());
    }
}