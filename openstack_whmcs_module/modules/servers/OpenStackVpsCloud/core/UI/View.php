<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\UI;

use ModulesGarden\OpenStackVpsCloud\Components\AppNavBar\Breadcrumb;
use ModulesGarden\OpenStackVpsCloud\Components\OverlayComponents\OverlayComponents;
use ModulesGarden\OpenStackVpsCloud\Core\Helper\BuildUrl;
use ModulesGarden\OpenStackVpsCloud\Core\Helper\RandomStringGenerator;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View\AlertsBuilder;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View\BreadcrumbsBuilder;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View\NavBarBuilder;
use function ModulesGarden\OpenStackVpsCloud\Core\Helper\isAdmin;

/**
 *
 */
class View extends AbstractPartialView
{
    public function __construct()
    {
        $this->initDefaultComponents();
    }

    protected function initDefaultComponents()
    {
        $this->addElement(OverlayComponents::class);
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->buildRootElements($this->elements);
    }
}
