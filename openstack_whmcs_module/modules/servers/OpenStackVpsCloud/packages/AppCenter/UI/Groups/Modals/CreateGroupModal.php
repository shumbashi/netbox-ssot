<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Groups\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertInfo;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\Modal;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalEdit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Groups\Forms\CreateGroupForm;

class CreateGroupModal extends ModalEdit implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setSize(Modal::SIZE_EXTRA_LARGE);

        $alert = (new AlertInfo())
            ->setText($this->translate("group_info"));

        $this->addElement($alert);
        $this->addElement(new CreateGroupForm());
    }
}