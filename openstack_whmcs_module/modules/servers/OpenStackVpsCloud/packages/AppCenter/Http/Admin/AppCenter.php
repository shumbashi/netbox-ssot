<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Http\Admin;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Helper;
use ModulesGarden\OpenStackVpsCloud\Core\Helper\BuildUrl;
use ModulesGarden\OpenStackVpsCloud\Core\Http\AbstractController;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Breadcrumbs;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Breadcrumbs\Item;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Pages\ItemEditPage;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Pages\ItemsTabsWidget;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Widgets\GuideHintBox;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Groups\Pages\GroupsDataTable;

class AppCenter extends AbstractController implements AdminAreaInterface
{
    public function index()
    {
        Breadcrumbs::add(new Item(Translator::get("admin.breadcrumbs.AppCenter_apps"), BuildUrl::getUrl("AppCenter", "apps")));

        return $this->apps();
    }

    public function apps()
    {
        return Helper\view()
            ->addElement(GuideHintBox::class)
            ->addElement(ItemsTabsWidget::class);
    }

    public function groups()
    {
        return Helper\view()
            ->addElement(GuideHintBox::class)
            ->addElement(GroupsDataTable::class);
    }

    public function edit()
    {
        $this->addEditBreadcrumbs();

        return Helper\view()
            ->addElement(ItemEditPage::class);
    }

    public function addEditBreadcrumbs()
    {
        $breadCrumbEdit = new Item(Translator::get("admin.breadcrumbs.AppCenter_apps"),
            BuildUrl::getUrl("AppCenter", "apps"));

        $breadcrumbs = Breadcrumbs::get();
        $editItem = end($breadcrumbs);

        array_pop($breadcrumbs);

        Breadcrumbs::set($breadcrumbs);
        Breadcrumbs::add($breadCrumbEdit);
        Breadcrumbs::add($editItem);
    }
}
