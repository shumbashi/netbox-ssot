<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Modals;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Forms\MassDeleteFirewallRulesForm;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class MassDeleteFirewallRulesModal extends ModalDanger implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setContent($this->translate('deleteFirewallRulesConfirmMess'));
        $this->addElement(new MassDeleteFirewallRulesForm());
    }
}
