<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\AppControllers;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AppControllerInterface;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;

class ServerHttp extends \ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\AppController implements AppControllerInterface
{
    public function getControllerInstanceClass($callerName, $params)
    {
        $functionName = strtolower(str_replace(ModuleConstants::getModuleName() . '_', '', $callerName));

        switch ($functionName)
        {
            case 'clientarea':
                return $params['model'] instanceof \WHMCS\Service\Addon ?
                    \ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Server\ClientPageProductAddonController::class :
                    \ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Server\ClientPageController::class ;
            default:
                throw new \Exception($functionName . ' is not implemented in ' . __CLASS__);
        }

        return null;
    }
}
