<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\UI\View;

use ModulesGarden\OpenStackVpsCloud\Components\AppBreadcrumb\AppBreadcrumb;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Breadcrumbs;

class BreadcrumbsBuilder
{
    public function create(): AppBreadcrumb
    {
        $breadcrumb = new AppBreadcrumb();
        $breadcrumb->setItems(Breadcrumbs::get());

        return $breadcrumb;
    }
}