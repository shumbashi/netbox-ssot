<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Modals;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Forms\AddFirewallRuleForm;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\Modal;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalEdit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class AddFirewallRuleModal extends ModalEdit implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setTitle($this->translate("title"));
        $this->addElement(new AddFirewallRuleForm());
    }
}