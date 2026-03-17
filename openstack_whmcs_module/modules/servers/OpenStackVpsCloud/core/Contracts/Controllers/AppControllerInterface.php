<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers;

interface AppControllerInterface
{
    public function getControllerInstanceClass($callerName, $params);
}
