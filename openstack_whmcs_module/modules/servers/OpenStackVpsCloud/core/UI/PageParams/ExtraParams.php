<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\UI\PageParams;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;

class ExtraParams
{
    public static function getForCurrentAction():array
    {
        $params = Params::all();

        $moduleAction = ModuleActionsFactory::getFromParams($params);

        return $moduleAction->selectAppropriateParameters($params);
    }
}
