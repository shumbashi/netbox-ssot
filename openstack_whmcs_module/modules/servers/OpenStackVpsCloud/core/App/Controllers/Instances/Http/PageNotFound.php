<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Http;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View;

//@todo refactor me
class PageNotFound extends View implements AdminAreaInterface, ClientAreaInterface
{
    public function __construct()
    {
        parent::__construct();

        $zero = new \ModulesGarden\OpenStackVpsCloud\Components\PageNotFound\PageNotFound();
        $this->addElement($zero);
    }
}
