<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Helper;

use ModulesGarden\OpenStackVpsCloud\Core\Http\JsonResponse;
use ModulesGarden\OpenStackVpsCloud\Core\Http\RedirectResponse;
use ModulesGarden\OpenStackVpsCloud\Core\Http\Response;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\ServiceLocator;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Session;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View;
use ModulesGarden\OpenStackVpsCloud\Core\UI\ViewAjax;
use ModulesGarden\OpenStackVpsCloud\Core\UI\ViewIntegrationAddon;

if (!function_exists('\\ModulesGarden\\OpenStackVpsCloud\\Core\\Helper\\response'))
{
    /**
     * Create a new HTTP response with the given data.
     *
     * @param array $data Optional response data
     * @return Response HTTP response object
     */
    function response(array $data = [])
    {
        return Response::create()->setData($data);
    }
}

if (!function_exists('\\ModulesGarden\\OpenStackVpsCloud\\Core\\Helper\\redirect'))
{
    /**
     * Create a redirect response to a given controller and action.
     *
     * @param string|null $controller Controller name
     * @param string|null $action Action name
     * @param array $params Optional parameters
     * @return JsonResponse Redirect response object
     */
    function redirect($controller = null, $action = null, array $params = [])
    {
        return RedirectResponse::createMG($controller, $action, $params);
    }
}

if (!function_exists('\\ModulesGarden\\OpenStackVpsCloud\\Core\\Helper\\sl'))
{
    /**
     * Service locator helper. Returns an instance of the given class or calls a method on it.
     *
     * @param string $class Class name
     * @param string|null $method Optional method name
     * @return object|null The resolved service or method result
     * @deprecated Use make() instead
     */
    function sl($class, $method = null)
    {
        $return = null;

        if ($class != null && $method == null)
        {
            $return = ServiceLocator::call($class);
        }
        elseif ($class != null && $method != null)
        {
            $return = ServiceLocator::call($class, $method);
        }

        return $return;
    }
}

if (!function_exists('\\ModulesGarden\\OpenStackVpsCloud\\Core\\Helper\\isAdmin'))
{
    /**
     * Check if the current session is an admin session.
     *
     * @return bool True if admin, false otherwise
     */
    function isAdmin(): bool
    {
        return defined('ADMINAREA') && Session::get('adminid');
    }
}

if (!function_exists('\\ModulesGarden\\OpenStackVpsCloud\\Core\\Helper\\getAdminDirName'))
{
    /**
     * Get the WHMCS admin directory name.
     *
     * @return string Admin directory name
     */
    function getAdminDirName()
    {
        $fileName = 'configuration.php';
        $filePath = ModuleConstants::getFullPathWhmcs();

        global $customadminpath;
        if (!$customadminpath && file_exists($filePath . DIRECTORY_SEPARATOR . $fileName))
        {
            include_once $filePath . DIRECTORY_SEPARATOR . $fileName;
        }

        if ($customadminpath && is_string($customadminpath))
        {
            return $customadminpath;
        }

        return 'admin';
    }
}

if (!function_exists('\\ModulesGarden\\OpenStackVpsCloud\\Core\\Helper\\view'))
{
    /**
     * Get the appropriate view instance based on the request.
     *
     * @return View|ViewAjax The view instance
     */
    function view()
    {
        if (Request::get('ajax') && Request::get('namespace') != null && Request::get('namespace') != '' && Request::get('namespace') != 'undefined')
        {
            return new ViewAjax();
        }

        return new View();
    }
}

if (!function_exists('\\ModulesGarden\\OpenStackVpsCloud\\Core\\Helper\\viewIntegrationAddon'))
{
    /**
     * View Integration Addon Controler
     *
     * @return ViewIntegrationAddon
     */
    function viewIntegrationAddon()
    {
        if (Request::get('ajax') && Request::get('namespace') != null && Request::get('namespace') != '' && Request::get('namespace') != 'undefined')
        {
            return new ViewAjax();
        }

        return new ViewIntegrationAddon();
    }
}

if (!function_exists('\\ModulesGarden\\OpenStackVpsCloud\\Core\\Helper\\fire'))
{
    /**
     * @deprecated - use \ModulesGarden\OpenStackVpsCloud\Core\fire
     */
    function fire($event)
    {
        return \ModulesGarden\OpenStackVpsCloud\Core\fire($event);
    }
}
