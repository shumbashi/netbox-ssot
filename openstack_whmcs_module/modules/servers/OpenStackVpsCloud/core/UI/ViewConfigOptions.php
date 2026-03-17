<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\UI;

use ModulesGarden\OpenStackVpsCloud\Core\Exceptions\UserException;
use ModulesGarden\OpenStackVpsCloud\Core\Helper\BuildUrl;
use ModulesGarden\OpenStackVpsCloud\Core\UI\PageParams\ExtraParams;
use ModulesGarden\OpenStackVpsCloud\Core\Helper\RandomStringGenerator;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Store;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Messages;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View\AlertsBuilder;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View\NavBarBuilder;

/**
 * Integration Addon View Controller
 */
class ViewConfigOptions extends View
{
    protected $template = 'configoptions';

    public function getResponse()
    {
        try
        {
            //@todo - refactor me!
            if (Request::get('ajax'))
            {
                foreach ($this->getElements() as $element)
                {
                    $viewAjax = new ViewAjax();
                    $viewAjax->addElement($element);

                    return $viewAjax->getResponse();
                }
            }

            return \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Smarty::view($this->template, [
                'rootElements'    => json_encode([
                    'navbar' => $this->buildRootElements([(new NavBarBuilder())->createProductConfiguration()])[0],
                    //                'breadcrumb' => (new BreadcrumbsBuilder())->create(),//$this->buildRootElements([])[0],
                    'body'   => $this->buildRootElements($this->elements),
                    'alerts' => (new AlertsBuilder())->create()
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
            ], ModuleConstants::getTemplateDir() . '/controllers');

        }
        catch (UserException $ex)
        {

            Messages::alert($ex->getMessage());

            return \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Smarty::view($this->template, [
                'rootElements'    => json_encode([
                    'alerts' => (new AlertsBuilder())->create()
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
            ], ModuleConstants::getTemplateDir() . '/controllers');
        }
    }
}
