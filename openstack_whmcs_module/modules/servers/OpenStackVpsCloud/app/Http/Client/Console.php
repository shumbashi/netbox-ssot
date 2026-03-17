<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Http\Client;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\SidebarHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\ConsoleManager;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Http\AbstractClientController;
use ModulesGarden\OpenStackVpsCloud\Core\Http\RedirectResponse;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Smarty;

class Console extends AbstractClientController implements ClientAreaInterface
{
    public function index()
    {
        if (empty(Params::get('customfields.vmID'))) {
            return null;
        }

        if (!(new SideBarHelper())->isEnabled('openstackVpsCloudManagement', Request::get('mg-page'))) {
            return null;
        }

        $consoleUrl = (new ConsoleManager(Params::get('customfields.vmID')))
            ->getConsoleUrl();

        $productConfig = new ProductConfiguration(Params::get('serviceid'));
        if($productConfig->getNewConsoleWindow())
        {
            $this->cleanOutputBuffer();
            Smarty::setTemplateDir(ModuleConstants::getResourcesDir() . '/views/custom');
            echo Smarty::view('console', ['console' => $consoleUrl]);
            exit;
        }

        return new RedirectResponse($consoleUrl);
    }
}