<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Http\Client;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\SidebarHelper;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Http\AbstractClientController;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\Helper;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Install\Widgets\ItemsTabsWidget;

class Reinstallation extends AbstractClientController implements ClientAreaInterface
{
    public function index()
    {
        if (empty(Params::get('customfields.vmID'))) {
            return null;
        }

        if (!(new SideBarHelper())->isEnabled('openstackVpsCloudManagement', Request::get('mg-page'))) {
            return null;
        }

        return Helper\view()
            ->addElement(ItemsTabsWidget::class);
    }
}