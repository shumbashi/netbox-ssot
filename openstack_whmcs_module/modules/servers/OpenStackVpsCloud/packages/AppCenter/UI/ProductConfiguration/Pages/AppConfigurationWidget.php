<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\ProductConfiguration\Pages;

use ModulesGarden\OpenStackVpsCloud\Components\Widget\Widget;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\ProductConfiguration\Forms\AppConfigForm;

class AppConfigurationWidget extends Widget implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setTitle($this->translate('title'));
        $this->addElement(new AppConfigForm());
    }
}