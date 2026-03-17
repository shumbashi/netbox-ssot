<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\Redirect;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\URL;

class FirewallButton extends VpsActionButtonCritical implements ClientAreaInterface, AdminAreaInterface
{
    public function loadHtml(): void
    {
        parent::loadHtml();
        $this->setImagePath('firewall.png');
        $this->onClick((new Redirect($this->createRawUrl())));
    }

    private function createRawUrl()
    {
        return URL\Client::productDetails(Request::get('id', 0), [
            'mg-page' => 'firewall',
            'modop' => 'custom',
            'a' => 'management']);
    }
}