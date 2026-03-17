<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Server;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;

class ClientPageProductAddonController extends ClientPageController implements ClientAreaInterface
{
    public function execute($params = null)
    {
        return \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Smarty::view('clientarea', parent::run($params), ModuleConstants::getTemplateDir() . '/controllers');
    }
}