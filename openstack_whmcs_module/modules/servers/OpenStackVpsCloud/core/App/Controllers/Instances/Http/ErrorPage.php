<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Http;

use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\HttpController;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Http\JsonResponse;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View;

//@todo refactor
class ErrorPage extends HttpController implements AdminAreaInterface, ClientAreaInterface
{
    public function execute($params = null)
    {
        Params::createFrom((array)$params);

        return $this->resolveResponse();
    }

    public function resolveResponse()
    {
        if (Request::get('ajax'))
        {
            return (new JsonResponse())->withError(Params::get('exception')->getMessage());
        }
        else
        {
            $error = new \ModulesGarden\OpenStackVpsCloud\Components\BlockError\BlockError();
            $error->setException(Params::get('exception'));

            $view = new View();
            $view->addElement($error);

            return $view;
        }
    }
}
