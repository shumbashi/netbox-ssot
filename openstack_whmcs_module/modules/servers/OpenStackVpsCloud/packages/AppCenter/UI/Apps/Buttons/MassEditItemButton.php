<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonEdit;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalOpen;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Modals\MassEditItemModal;

class MassEditItemButton extends IconButtonEdit
{
    public function loadHtml(): void
    {
        parent::loadHtml();

        $this->displayWithTitle($this->translate('edit_mass'));
        $this->onClick(new ModalOpen(new MassEditItemModal()));
    }
}