<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Admin\Dashboard\Pages;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class Container extends \ModulesGarden\OpenStackVpsCloud\Components\Container\Container implements AdminAreaInterface
{
    public function loadHtml(): void
    {
        $servers = new ServersDataTable();
        $this->addElement($servers);
    }
}