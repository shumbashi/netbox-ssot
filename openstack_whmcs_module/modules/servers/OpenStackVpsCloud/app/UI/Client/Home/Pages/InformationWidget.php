<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Pages;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Tables\InformationTable;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxAutoReloadInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Components\Widget\Widget;

class InformationWidget extends Widget implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface, AjaxAutoReloadInterface
{
    const ID = 'information_widget';

    public function loadHtml(): void
    {
        $this->setId(self::ID);
        $this->setTitle($this->translate("title"));
        $this->addElement(new InformationTable());
    }
}