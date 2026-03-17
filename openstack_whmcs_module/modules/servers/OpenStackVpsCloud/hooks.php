<?php

if (!defined('WHMCS'))
{
    die('This file cannot be accessed directly');
}

#MGLICENSE_FUNCTIONS#

require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Bootstrap.php';

\ModulesGarden\OpenStackVpsCloud\Core\Hook\HookManager::create(__DIR__);
