<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Widgets;

use ModulesGarden\OpenStackVpsCloud\Components\TabsWidget\TabsWidget;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\ConfigSettings;

class TaskDetailsTabs extends TabsWidget implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->addTab(new TaskDetailsTab());
        $this->addTab(new TaskLogsTab());

        $this->checkJobAdditionalInfo();
    }

    protected function checkJobAdditionalInfo():void
    {
        $additionalInfoElements = Config::get(ConfigSettings::JOB_ADDITIONAL_INFORMATION, []);

        if (is_callable($additionalInfoElements))
        {
            $additionalInfoElements = call_user_func($additionalInfoElements, Request::get('formData'));
        }

        if (!is_array($additionalInfoElements) || empty($additionalInfoElements))
        {
            return;
        }

        $this->addTab((new TaskAdditionalInfoTab())->setAdditionalInfoElements($additionalInfoElements));
    }
}