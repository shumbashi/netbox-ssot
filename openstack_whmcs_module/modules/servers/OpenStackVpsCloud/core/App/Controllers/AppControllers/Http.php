<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\AppControllers;

use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Http\AdminPageController;
use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Http\ClientPageController;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AppControllerInterface;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;

class Http extends \ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\AppController implements AppControllerInterface
{
    public function getControllerInstanceClass($callerName, $params)
    {
        $functionName = strtolower(str_replace(ModuleConstants::getModuleName() . '_', '', $callerName));
        switch ($functionName)
        {
            //HTTP controllers
            case 'output':
                return AdminPageController::class;
            case 'clientarea':
                return ClientPageController::class;
        }

        return null;
    }
}
