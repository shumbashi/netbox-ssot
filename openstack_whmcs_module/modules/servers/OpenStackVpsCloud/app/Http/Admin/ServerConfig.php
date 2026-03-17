<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Http\Admin;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Helper;
use ModulesGarden\OpenStackVpsCloud\Core\Http\AbstractController;

class ServerConfig extends AbstractController implements AdminAreaInterface
{
    public function index()
    {
        return Helper\viewIntegrationAddon()
            ->addElement(\ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Buttons\ServerConfiguration::class);
    }
}
