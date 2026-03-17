<?php

use ModulesGarden\OpenStackVpsCloud\Core\App\AppContext;

if (!defined('WHMCS'))
{
    die('This file cannot be accessed directly');
}

require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';


function OpenStackVpsCloud_CreateAccount(array $params)
{
    

    return (new AppContext())->runServerModule(__FUNCTION__, $params);
}

function OpenStackVpsCloud_SuspendAccount(array $params)
{
    

    return (new AppContext())->runServerModule(__FUNCTION__, $params);
}

function OpenStackVpsCloud_UnsuspendAccount(array $params)
{
    

    return (new AppContext())->runServerModule(__FUNCTION__, $params);
}

function OpenStackVpsCloud_TerminateAccount(array $params)
{
    

    return (new AppContext())->runServerModule(__FUNCTION__, $params);
}


function OpenStackVpsCloud_ChangePackage(array $params)
{
    

    return (new AppContext())->runServerModule(__FUNCTION__, $params);
}

function OpenStackVpsCloud_TestConnection(array $params)
{
    try
    {
        
    }
    catch (\Exception $ex)
    {
        return [
            'error' => $ex->getMessage()
        ];
    }

    return (new AppContext())->runServerModule(__FUNCTION__, $params);
}
function OpenStackVpsCloud_UsageUpdate(array $params)
{
    return (new AppContext())->runServerModule(__FUNCTION__, $params);
}

function OpenStackVpsCloud_ConfigOptions($params = [])
{
    return (new AppContext())->runServerModule(__FUNCTION__, $params);
}

//function OpenStackVpsCloud_ServiceSingleSignon($params)
//{
//    return OpenStackVpsCloud_execute(__FUNCTION__, $params);
//}

function OpenStackVpsCloud_MetaData()
{
    return (new AppContext())->runServerModule(__FUNCTION__, $params);
}

//function OpenStackVpsCloud_AdminSingleSignOn($params)
//{
//    return OpenStackVpsCloud_execute(__FUNCTION__, $params);
//}

function OpenStackVpsCloud_AdminServicesTabFields($params)
{
    return (new AppContext())->runServerModule(__FUNCTION__, $params);
}

if (defined('CLIENTAREA') && !function_exists('OpenStackVpsCloud_ClientArea')) {
    function OpenStackVpsCloud_ClientArea($params)
    {
        

        return (new AppContext())->runServerModule(__FUNCTION__, $params);
    }
}
