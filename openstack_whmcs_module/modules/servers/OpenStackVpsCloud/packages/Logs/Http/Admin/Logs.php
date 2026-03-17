<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\Http\Admin;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Helper;
use ModulesGarden\OpenStackVpsCloud\Core\Http\AbstractController;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Pages\LogsDataTable;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Widgets\Summary;

class Logs extends AbstractController implements AdminAreaInterface
{
    /**
     * Example of static page
     * @return View
     */
    public function index()
    {
        return Helper\view()
            ->addElement(Summary::class)
            ->addElement(LogsDataTable::class);
    }
}
