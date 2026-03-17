<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Translations\Tabs;

use ModulesGarden\OpenStackVpsCloud\Components\Tab\Tab;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\DynamicTranslations\Pages\DynamicTranslationsPage;

class DynamicTranslationsTab extends Tab
{
    public function loadHtml():void
    {
        $this->setTitle($this->translate('dynamicTranslationsTabTitle'));
        $this->addElement(new DynamicTranslationsPage());
    }
}