<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Widgets;

use ModulesGarden\OpenStackVpsCloud\Components\Widget\Widget;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Forms\HostnameGenerateForm;

class HostnameGenerateWidget extends Widget
{
    public function loadHtml(): void
    {
        $this->setTitle($this->translate('title'));

        $this->addElement(new HostnameGenerateForm());
    }
}