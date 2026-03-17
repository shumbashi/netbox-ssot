<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Http\Admin;

use ModulesGarden\OpenStackVpsCloud\Core\Http\AbstractController;

class PageNotFound extends AbstractController
{
    public function index()
    {
        $pageControler = new \ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Http\PageNotFound();

        return $pageControler->execute();
    }
}
