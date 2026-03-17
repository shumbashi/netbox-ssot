<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Http\Client;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\SidebarHelper;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Http\AbstractClientController;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Pages\BackupsTable;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Pages\ScheduledBackupConf;
use ModulesGarden\OpenStackVpsCloud\Core\Helper;
use function ModulesGarden\OpenStackVpsCloud\Core\Helper;

class Backups extends AbstractClientController implements ClientAreaInterface
{
    public function index()
    {
        if (empty(Params::get('customfields.vmID'))) {
            return null;
        }

        if (!(new SideBarHelper())->isEnabled('openstackVpsCloudManagement', Request::get('mg-page'))) {
            return null;
        }

        $view = Helper\view()
            ->addElement(BackupsTable::class);

        $productConfig = new ProductConfiguration(Params::get('serviceid'));
        if ($productConfig->getScheduledBackups() && $productConfig->getClientScheduledBackups())
        {
            $view->addElement(ScheduledBackupConf::class);
        }

        return $view;
    }
}