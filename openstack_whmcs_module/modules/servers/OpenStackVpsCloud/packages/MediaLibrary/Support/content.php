<?php

use ModulesGarden\OpenStackVpsCloud\Core\Helper\OutputBufferHelper;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Services\FileLoader;

$componentsPath = dirname(__DIR__);

$whmcsPath  = dirname($componentsPath, 5);
$modulePath = dirname($componentsPath, 2);

require_once $whmcsPath . "/vendor/autoload.php";
require_once $modulePath . "/vendor/autoload.php";
require_once $modulePath . "/core/functions.php";

ModuleConstants::initialize();


try
{
    $loader = new FileLoader(Request::get('fileName'));

    OutputBufferHelper::clean();
    
    header('Content-Length: ' . $loader->getSize());
    header('Content-Type: ' . $loader->getType());
    echo $loader->read();
}
catch (Exception $e)
{
    header('Status: 500 Internal Server Error', true, 500);
}