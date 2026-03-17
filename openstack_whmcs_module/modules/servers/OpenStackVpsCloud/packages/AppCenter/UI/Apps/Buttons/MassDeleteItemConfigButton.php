<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonDelete;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalOpen;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Modals\MassDeleteItemConfigModal;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Modals\MassDeleteItemModal;

class MassDeleteItemConfigButton extends IconButtonDelete
{
    public function loadHtml(): void
    {
        parent::loadHtml();

        $this->displayWithTitle($this->translate('delete_mass'));
        $this->onClick(new ModalOpen(new MassDeleteItemConfigModal()));
    }
}