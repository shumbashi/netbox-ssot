<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Http;

use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\HttpController;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Http\JsonResponse;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\URL\Client;

class AuthFailedController extends HttpController implements AdminAreaInterface, ClientAreaInterface
{
    public function execute($params = null)
    {
        return $this->resolveResponse();
    }

    public function resolveResponse()
    {
        if (Request::get('ajax'))
        {
            return (new JsonResponse())
                ->withError("Authentication Required");
        }

        Client::redirectToLoginPage();
    }
}
