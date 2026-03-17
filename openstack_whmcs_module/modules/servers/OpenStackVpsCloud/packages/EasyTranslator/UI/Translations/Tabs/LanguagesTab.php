<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Translations\Tabs;

use ModulesGarden\OpenStackVpsCloud\Components\Tab\Tab;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Pages\LanguagesTable;

class LanguagesTab extends Tab
{
    public function loadHtml():void
    {
        $this->setTitle($this->translate('languagesTabTitle'));
        $this->addElement(new LanguagesTable());
    }
}