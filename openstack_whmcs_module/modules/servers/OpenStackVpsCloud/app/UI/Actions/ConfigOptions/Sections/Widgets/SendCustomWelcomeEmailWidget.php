<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Widgets;

use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Forms\SendCustomWelcomeEmail;
use ModulesGarden\OpenStackVpsCloud\Components\Widget\Widget;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class SendCustomWelcomeEmailWidget extends Widget implements AdminAreaInterface {
    public function loadHtml(): void
    {
        parent::loadHtml();

        $this->setTitle($this->translate('title'));
        $this->addElement(new SendCustomWelcomeEmail());
    }
}

