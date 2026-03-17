<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Http;

use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\HttpController;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\UI\AbstractPartialView;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Views\AddonModuleAdminArea;

class AdminPageController extends HttpController implements \ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AdminAreaInterface
{
    public function execute($params = null)
    {
        return \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Smarty::view('adminarea', parent::run($params), ModuleConstants::getTemplateDir() . '/controllers');
    }

    protected function preResolveResponse()
    {
        if($this->controllerResult instanceof View)
        {
            $this->controllerResult = new AddonModuleAdminArea($this->controllerResult);
        }
    }
}
