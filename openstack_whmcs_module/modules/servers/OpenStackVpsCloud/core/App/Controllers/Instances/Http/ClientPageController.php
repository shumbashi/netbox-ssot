<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Http;

use ModulesGarden\OpenStackVpsCloud\App\Hooks\InternalHooks\PreClientAreaPageLoad;
use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\HttpController;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Routing\Url;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Breadcrumbs;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Views\AddonModuleClientArea;

class ClientPageController extends HttpController implements ClientAreaInterface
{
    public function execute($params = null)
    {
        $vars = parent::run($params);

        return [
            'pagetitle'    => Translator::get(Config::get('configuration.clientAreaName', ModuleConstants::getModuleName())),
            'breadcrumb'   => $this->getBreadcrumbs(),
            'templatefile' => 'resources/whmcs/clientarea',
            'requirelogin' => true,
            'forcessl'     => false,
            'vars'         => [
                'content' => \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Smarty::view('clientarea', $vars, ModuleConstants::getTemplateDir() . '/controllers')
            ]
        ];
    }

    protected function preResolveResponse()
    {
        if ($this->controllerResult instanceof View)
        {
            $this->controllerResult = new AddonModuleClientArea($this->controllerResult);
        }
    }

    protected function getBreadcrumbs(): array
    {
        $breadcrumbs = [
            Url::route() => Translator::get(Config::get('configuration.clientAreaName'))
        ];

        foreach (Breadcrumbs::get() as $breadcrumb)
        {
            $breadcrumbs[$breadcrumb->getUrl()] = Translator::get($breadcrumb->getName());
        }

        return $breadcrumbs;
    }
}
