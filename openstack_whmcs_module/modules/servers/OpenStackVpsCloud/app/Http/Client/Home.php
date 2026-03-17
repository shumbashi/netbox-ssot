<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Http\Client;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Pages\InformationWidget;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Pages\ServiceActionsForm;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Tables\InterfacesTable;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Tables\VolumesTable;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Helper;
use ModulesGarden\OpenStackVpsCloud\Core\Http\AbstractClientController;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Fragments\ScheduledTasks\DataTables\ServiceScheduledTasks;

class Home extends AbstractClientController implements ClientAreaInterface
{
    protected ?array $productConfiguration = null;

    public function index()
    {
        if (empty(Params::get('customfields.vmID'))) {
            return null;
        }

        $view = Helper\view();

        if (Params::get('status') == 'Active') {
            $view->addElement(ServiceActionsForm::class);
            $view->addElement(InformationWidget::class);
            $view->addElement(InterfacesTable::class);
            $view->addElement(VolumesTable::class);
        }

        $this->productConfiguration =(new ProductConfiguration(Params::get('packageid')))->get();
        if ($this->productConfiguration['caf_scheduled_logs']) {
            $view->addElement(ServiceScheduledTasks::class);
        }

        return $view;
    }
}
