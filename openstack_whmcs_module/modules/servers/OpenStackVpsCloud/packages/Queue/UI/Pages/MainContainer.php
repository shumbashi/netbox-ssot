<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Pages;

use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Packages\ModuleSettings\Support\Facades\ModuleSettings;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\ConfigSettings;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\ModuleSettings as Settings;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\DataTable\Queue;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Widgets\HintsBox;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Widgets\QueueSummaryWidget;

class MainContainer extends Container implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        //TODO pasowałoby to zapewne przerobić na fabrykę jeśli dojdzie inny hint
        if (!Config::get(ConfigSettings::DISABLE_GUIDE, false) &&
            !Config::get(ConfigSettings::DISABLE_CRON_INFO_HINT, false) &&
            ModuleSettings::get(Settings::SHOW_CRON_INFO, true))
        {
            $this->addElement(new HintsBox());
        }

        $this->addElement(new QueueSummaryWidget());
        $this->addElement(new Queue());
    }
}