<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\UI\View;

use ModulesGarden\OpenStackVpsCloud\Components\AppFooter\AppFooter;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;

class FooterBuilder
{
    protected AppFooter $appFooter;
    public function __construct()
    {
        $this->appFooter = new AppFooter();
    }

    public function create(): AppFooter
    {
        $this->configureFooter();

        return  $this->appFooter;
    }

    public function configureFooter(): void
    {
        $this->appFooter->setModuleName(Config::get('configuration.systemName'));
        $this->appFooter->setModuleVersion(Config::get('configuration.version'));
        $this->appFooter->hideModuleVersion(Config::get('configuration.hideModuleVersion', false));
    }
}