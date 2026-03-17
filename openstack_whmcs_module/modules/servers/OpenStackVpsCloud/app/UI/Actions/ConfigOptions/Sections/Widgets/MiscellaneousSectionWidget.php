<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Widgets;

use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Forms\MiscellaneousSection;
use ModulesGarden\OpenStackVpsCloud\Components\Widget\Widget;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class MiscellaneousSectionWidget extends Widget implements AdminAreaInterface {
    public function loadHtml(): void
    {
        parent::loadHtml();

        $this->setTitle($this->translate('title'));
        $this->addElement(new MiscellaneousSection());
    }
}

