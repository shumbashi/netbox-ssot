<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertInfo;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Forms\RefreshItemsForm;

class RefreshItemsModal extends ModalDanger implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $alert = new AlertInfo();
        $alert->setText($this->translate('description'));

        $this->addElement($alert);
        $this->addElement(new RefreshItemsForm());
    }
}