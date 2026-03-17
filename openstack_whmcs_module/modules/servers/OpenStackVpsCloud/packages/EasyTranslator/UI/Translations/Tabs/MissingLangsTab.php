<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Translations\Tabs;

use ModulesGarden\OpenStackVpsCloud\Components\Tab\Tab;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\MissingLangs\Pages\MissingLangsPage;

class MissingLangsTab extends Tab
{
    public function loadHtml():void
    {
        $this->setTitle($this->translate('missingLangsTabTitle'));
        $this->addElement(new MissingLangsPage());
    }
}