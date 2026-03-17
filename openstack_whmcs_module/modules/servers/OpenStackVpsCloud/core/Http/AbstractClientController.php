<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Http;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\NoAuthenticateControllerInterface;

/**
 * Description of AbstractClientController
 */
class AbstractClientController extends AbstractController
{
    public function __construct()
    {
        $this->authenticateClient();
    }

    protected function authenticateClient():void
    {
        if ($this instanceof NoAuthenticateControllerInterface)
        {
            return;
        }

        \Auth::requireLoginAndClient();
    }
}
