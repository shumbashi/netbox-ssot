<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\AppControllers;

use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AppControllerInterface;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;

class MetaData extends \ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\AppController implements AppControllerInterface
{
    public function __construct(array $params = [])
    {
        //override parent, do not populate params
    }
    public function getControllerInstanceClass($callerName, $params)
    {
        $functionName = str_replace(ModuleConstants::getModuleName() . '_', '', $callerName);

        $coreAddon = '\ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Addon\\' . ucfirst($functionName);
        if (class_exists($coreAddon) && is_subclass_of($coreAddon, AddonController::class))
        {
            return $coreAddon;
        }

        $appAddon = '\ModulesGarden\OpenStackVpsCloud\App\Http\Actions\\' . ucfirst($functionName);
        if (class_exists($appAddon) && is_subclass_of($appAddon, AddonController::class))
        {
            return $appAddon;
        }

        return null;
    }
}
