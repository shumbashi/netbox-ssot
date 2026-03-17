<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances;

use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Http\AddonIntegration;
use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Http\AdminServicesTabFieldsIntegration;
use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Http\ConfigOptionsIntegration;
use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\ResponseResolver;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\DefaultControllerInterface;
use ModulesGarden\OpenStackVpsCloud\Core\UI\ViewAjax;
use ModulesGarden\OpenStackVpsCloud\Core\UI\ViewConfigOptions;
use ModulesGarden\OpenStackVpsCloud\Core\UI\ViewIntegrationAddon;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Messages;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use function ModulesGarden\OpenStackVpsCloud\Core\make;

abstract class AddonController implements DefaultControllerInterface
{
    public function runExecuteProcess($params = null)
    {
        $result = $this->execute($params);

        if ($this->isValidIntegrationCallback($result))
        {
            $method = $result[1];

            try
            {
                $result = make($result[0])->$method();
            }
            catch (\Exception $e)
            {
                Messages::alert($e->getMessage());
                $result = (Request::get('ajax') && !empty(Request::get('namespace'))) ? new ViewAjax() : new ViewIntegrationAddon();
            }
        }

        if ($result instanceof ViewAjax)
        {
            $this->resolveAjax($result);
        }

        if (!$result instanceof ViewIntegrationAddon && !$result instanceof ViewConfigOptions)
        {
            return $result;
        }

        $addonIntegration = $this->getIntegrationController($params['action']);

        return $addonIntegration->runExecuteProcess($result);
    }

    public function isValidIntegrationCallback($callback = null)
    {
        return is_array($callback) && isset($callback[0]) && isset($callback[1]) && method_exists($callback[0], $callback[1]);
    }

    public function resolveAjax($resault)
    {
        $ajaxResponse = $resault->getResponse();

        $resolver = new ResponseResolver($ajaxResponse);

        $resolver->resolve();
    }

    protected function getIntegrationController($action = null)
    {
        switch ($action)
        {
            case 'ConfigOptions':
                return make(ConfigOptionsIntegration::class);
            case 'AdminServicesTabFields':
                return make(AdminServicesTabFieldsIntegration::class);
            default:
                return make(AddonIntegration::class);
        }
    }
}
