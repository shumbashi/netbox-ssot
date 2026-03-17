<?php
// phpcs:ignoreFile

use ModulesGarden\OpenStackVpsCloud\Core\App\AppContext;

if (!defined('WHMCS'))
{
    die('This file cannot be accessed directly');
}

require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'AppContext.php';

function OpenStackVpsCloud_config()
{
    return (new AppContext())->runAddonModule(__FUNCTION__, []);
}

function OpenStackVpsCloud_activate()
{
    return (new AppContext())->runAddonModule(__FUNCTION__, []);
}

function OpenStackVpsCloud_deactivate()
{
    return (new AppContext())->runAddonModule(__FUNCTION__, []);
}

function OpenStackVpsCloud_upgrade($params)
{
    return (new AppContext())->runAddonModule(__FUNCTION__, []);
}

function OpenStackVpsCloud_output($params)
{
    #MGLICENSE_CHECK_ECHO_AND_RETURN_MESSAGE#

    $html = (new AppContext())->runAddonModule(__FUNCTION__, $params);

    if (is_array($html)) {
        print_r($html);
    } elseif ($html) {
        echo $html;
    }

}
function OpenStackVpsCloud_clientarea($params)
{
    #MGLICENSE_CHECK_ECHO_AND_RETURN_MESSAGE#

    $html = (new AppContext())->runAddonModule(__FUNCTION__, $params);
}