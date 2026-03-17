<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Translations\Pages;

use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Translations\Widgets\LanguagesTabsWidget;
use ModulesGarden\OpenStackVpsCloud\Packages\ModuleSettings\Support\Facades\ModuleSettings;

class TranslationsPage extends Container implements AdminAreaInterface, AjaxComponentInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('translationsPage');
    }

    public function loadHtml():void
    {
        $this->addElement(new LanguagesTabsWidget());
    }
}