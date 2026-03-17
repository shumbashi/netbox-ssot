<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Http\Admin;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Helper;
use ModulesGarden\OpenStackVpsCloud\Core\Http\AbstractController;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Pages\MainContainer;

class Queue extends AbstractController implements AdminAreaInterface
{
    /**
     * Example of static page
     * @return View
     */
    public function index()
    {
        return Helper\view()
            ->addElement(MainContainer::class);
    }
}
