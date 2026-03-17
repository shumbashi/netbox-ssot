<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Server;

use ModulesGarden\OpenStackVpsCloud\App\Hooks\InternalHooks\PreClientAreaPageLoad;
use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\HttpController;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Router;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Views\ServerModuleClientArea;

class ClientPageController extends HttpController implements ClientAreaInterface
{
    public function execute($params = null)
    {
        $data             = parent::run($params);

        $templateType     = Router::getCurrentRoute()->is(Config::get('configuration.clientAreaController'), 'index') ? 'templatefile' : 'tabOverviewReplacementTemplate';
        $baseTemplateFile = 'resources/whmcs/clientarea';
        $templateFilePath = ModuleConstants::getModuleType() == 'addons' ? '../../../modules/addons/'.ModuleConstants::getModuleName().'/' . $baseTemplateFile : $baseTemplateFile;

        return [
            'pagetitle'    => Translator::get(Config::get('configuration.clientAreaName', ModuleConstants::getModuleName())),
            'breadcrumb'   => [],
            $templateType  => $templateFilePath,
            'requirelogin' => true,
            'forcessl'     => false,
            'vars'         => [
                'content' => \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Smarty::view('clientarea', $data, ModuleConstants::getTemplateDir() . '/controllers')
            ]
        ];
    }

    protected function preResolveResponse()
    {
        if ($this->controllerResult instanceof View)
        {
            $this->controllerResult = new ServerModuleClientArea($this->controllerResult);
        }
    }
}
