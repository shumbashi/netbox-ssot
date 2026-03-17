<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Http\Admin;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Pages\BackupsTable;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Pages\FirewallTable;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Pages\InformationWidget;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Pages\ServiceActionsForm;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Tables\InterfacesTable;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Tables\VolumesTable;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Pages\SnapshotsTable;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Helper;
use ModulesGarden\OpenStackVpsCloud\Core\Http\AbstractController;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Install\Widgets\ItemsTabsWidget;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Fragments\ScheduledTasks\DataTables\ServiceScheduledTasks;

class Home extends AbstractController implements AdminAreaInterface
{
    public function productIndex()
    {
        $productConfig = (new ProductConfiguration(Params::get('packageid')))->get();

        if (empty(Params::get('customfields.vmID'))) {
            return null;
        }

        $view = Helper\viewIntegrationAddon()
            ->addElement(ServiceActionsForm::class)
            ->addElement(InformationWidget::class);

        if ($productConfig['aaf_rebuild']) {
            $view->addElement(ItemsTabsWidget::class);
        }

        if ($productConfig['aaf_interfaces']) {
            $view->addElement(InterfacesTable::class);
        }

        if ($productConfig['aaf_volumes']) {
            $view->addElement(VolumesTable::class);
        }

        if ($productConfig['aaf_firewall']) {
            $view->addElement(FirewallTable::class);
        }

        if ($productConfig['aaf_backups']) {
            $view->addElement(BackupsTable::class);
        }

        if ($productConfig['aaf_snapshots']) {
            $view->addElement(SnapshotsTable::class);
        }

        if ($productConfig['aaf_scheduled_logs']) {
            $view->addElement(ServiceScheduledTasks::class);
        }

        return $view;
    }
}