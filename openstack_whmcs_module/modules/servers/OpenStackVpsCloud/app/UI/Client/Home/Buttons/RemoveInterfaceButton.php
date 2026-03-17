<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Modals\RemoveInterfaceModal;
use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonDelete;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;

class RemoveInterfaceButton extends IconButtonDelete implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        if (JobManager::areCirticalBeingPerformed(Params::get('serviceid'))) {
            $this->setDisabled();
        }

        $this->onClick(new ModalLoad(new RemoveInterfaceModal()));
    }
}