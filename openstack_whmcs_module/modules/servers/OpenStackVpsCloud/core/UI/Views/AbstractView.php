<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\UI\Views;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Compiler\CompilerOutputFileInfo;
use ModulesGarden\OpenStackVpsCloud\Core\Components\DataBuilder;
use ModulesGarden\OpenStackVpsCloud\Core\Helper\BuildUrl;
use ModulesGarden\OpenStackVpsCloud\Core\Helper\RandomStringGenerator;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Store;
use ModulesGarden\OpenStackVpsCloud\Core\UI\AbstractPartialView;
use ModulesGarden\OpenStackVpsCloud\Core\UI\PageParams\ExtraParams;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View\AlertsBuilder;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View\BreadcrumbsBuilder;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View\FooterBuilder;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View\NavBarBuilder;
use function ModulesGarden\OpenStackVpsCloud\Core\Helper\isAdmin;

class AbstractView
{
    protected AbstractPartialView $view;

    public function __construct(AbstractPartialView $view)
    {
        $this->view = $view;
    }

    public function getResponse(): array
    {
        return array_merge([
            'rootElements' => json_encode(array_merge(
                $this->getBody(),
                $this->getNavbar(),
                $this->getBreadCrumb(),
                $this->getAlerts(),
                $this->getFooter(),
            )),
        ], $this->getBaseElements());
    }

    protected function getBaseElements(): array
    {
        return [
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
            'precompiledMode' => $this->isPrecompiledModeEnabled(),
            'compiledOutputUrl' => CompilerOutputFileInfo::getOutputFileUrl(),
            'integrationType' => 'module',
            'isAdminArea'     => isAdmin() ? 1 : 0,
        ];
    }

    protected function getBody(): array
    {
        return [
            'body' => $this->buildRootElements($this->view->getElements())
        ];
    }

    protected function getNavbar(): array
    {
        return [
            'navbar' => isAdmin() ? $this->buildRootElements([(new NavBarBuilder())->createAdminArea()])[0] : $this->buildRootElements([(new NavBarBuilder())->createClientArea()])[0],
        ];
    }

    protected function getBreadCrumb(): array
    {
        return [
            'breadcrumb' => $this->buildRootElements([(new BreadcrumbsBuilder())->create()])[0]
        ];
    }

    protected function getFooter(): array
    {
        return [
            'footer' => $this->buildRootElements([(new FooterBuilder())->create()])[0]
        ];
    }

    protected function getAlerts(): array
    {
        return [
            'alerts' => (new AlertsBuilder())->create()
        ];
    }

    //@todo refactor me
    protected function buildRootElements(array $rootElements)
    {
        return array_map(function($element) {
            return (new DataBuilder($element))
                ->withHtml()
                ->toArray();
        }, $rootElements);
    }

    protected function isPrecompiledModeEnabled(): bool
    {
        return \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('configuration.precompiledMode', false) &&
               !empty(file_get_contents(CompilerOutputFileInfo::getOutputFilePath()));
    }
}