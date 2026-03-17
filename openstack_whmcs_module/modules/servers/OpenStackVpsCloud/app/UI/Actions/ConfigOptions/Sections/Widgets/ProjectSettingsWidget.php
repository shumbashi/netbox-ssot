<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Widgets;

use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Forms\RefreshResourcesForm;
use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Forms\ProjectSettings;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonInfo;
use ModulesGarden\OpenStackVpsCloud\Components\Widget\Widget;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\FormSubmit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class ProjectSettingsWidget extends Widget implements AdminAreaInterface {
    public function loadHtml(): void
    {
        parent::loadHtml();

        $this->setTitle($this->translate('title'));
        $this->addElement(new ProjectSettings());
    }
}

