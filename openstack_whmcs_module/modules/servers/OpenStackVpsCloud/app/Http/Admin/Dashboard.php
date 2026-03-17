<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Http\Admin;

use ModulesGarden\OpenStackVpsCloud\App\UI\Admin\Dashboard\Pages\Container;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Http\AbstractController;
use function ModulesGarden\OpenStackVpsCloud\Core\Helper\view;

class Dashboard extends AbstractController implements AdminAreaInterface
{
    public function index()
    {
        return view()->addElement(Container::class);
    }
}