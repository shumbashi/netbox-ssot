<?php

namespace ModulesGarden\OpenStackVpsCloud\Fragments\ServerServicesTable\UI\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButton;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\Redirect;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Routing\Url;

class ServiceRedirectButton extends IconButton implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setTitle($this->translate("service_details"));
        $this->setIcon("information");

        $this->onClick((new Redirect(Url::adminarea('clientsservices.php'), [
            'userid' => 'client_id',
            'productselect' => 'service_id'
        ])));
    }
}