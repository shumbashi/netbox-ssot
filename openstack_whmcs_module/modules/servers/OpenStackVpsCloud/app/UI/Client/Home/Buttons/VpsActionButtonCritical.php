<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;

class VpsActionButtonCritical extends VpsActionButton
{
    public function loadHtml(): void
    {
        if (JobManager::areCirticalBeingPerformed(Params::get('serviceid'))) {
            $this->setDisabled();
        }

        parent::loadHtml();
    }
}