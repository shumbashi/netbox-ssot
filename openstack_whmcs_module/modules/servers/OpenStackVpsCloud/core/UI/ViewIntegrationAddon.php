<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\UI;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Store;
use ModulesGarden\OpenStackVpsCloud\Core\UI\PageParams\ExtraParams;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Messages;
use ModulesGarden\OpenStackVpsCloud\Core\Helper\BuildUrl;
use ModulesGarden\OpenStackVpsCloud\Core\Helper\RandomStringGenerator;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View\AlertsBuilder;

/**
 * Integration Addon View Controller
 */
class ViewIntegrationAddon extends View
{
    protected $integration = true;
    protected $template = 'integration';

    //@todo move it to views and controller
    public function getResponse()
    {
        try {
            return \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Smarty::view($this->template, [
                'rootElements'    => json_encode([
                    'body'       => $this->buildRootElements($this->elements),
                    'alerts'     => (new AlertsBuilder())->create()
                ]),
                'currentUrl'      => BuildUrl::currentUrl(),
                'moduleRequestUrl'=> BuildUrl::getModuleRequestUrl(),
                'componentsUrl'   => BuildUrl::getComponentsURL(),
                'extraParams'     => json_encode(ExtraParams::getForCurrentAction()),
                'assetsURL'       => BuildUrl::getAssetsURL(),
                'customAssetsURL' => BuildUrl::getAssetsURL(true),
                'vueInstanceName' => (new RandomStringGenerator(32))->genRandomString(ModuleConstants::getModuleName()),
                'vueStoreData'    => json_encode(Store::toArray()),
                'moduleName'      => ModuleConstants::getModuleName(),
                'moduleVersion'   => \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('configuration.version'),
                'integrationType' => 'integration',
            ], ModuleConstants::getTemplateDir() . '/controllers');
        }
        catch (\Exception $ex)
        {
            Messages::alert($ex->getMessage());

            return \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Smarty::view($this->template, [
                'rootElements'    => json_encode([
                    'alerts' => (new \ModulesGarden\OpenStackVpsCloud\Core\UI\View\AlertsBuilder())->create()
                ]),
                'currentUrl'      => \ModulesGarden\OpenStackVpsCloud\Core\Helper\BuildUrl::currentUrl(),
                'moduleRequestUrl'=> BuildUrl::getModuleRequestUrl(),
                'componentsUrl'   => BuildUrl::getComponentsURL(),
                'extraParams'     => json_encode(ExtraParams::getForCurrentAction()),
                'assetsURL'       => BuildUrl::getAssetsURL(),
                'customAssetsURL' => BuildUrl::getAssetsURL(true),
                'vueInstanceName' => (new RandomStringGenerator(32))->genRandomString(ModuleConstants::getModuleName()),
                'vueStoreData'    => json_encode(Store::toArray()),
                'moduleName'      => ModuleConstants::getModuleName(),
                'moduleVersion'   => \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('configuration.version'),
            ], ModuleConstants::getTemplateDir() . '/controllers');
        }
    }
}
