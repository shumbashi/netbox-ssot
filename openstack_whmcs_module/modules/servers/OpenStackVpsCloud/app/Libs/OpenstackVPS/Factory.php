<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Tenant;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ServerConfiguration;
use WHMCS\Database\Capsule as DB;

class Factory
{

    /**
     * @param array $params
     */
    public static function getTenantFromTestConnection(array $params)
    {
        return self::adminFromParams($params);
    }

    public static function adminFromServerId(int $severId)
    {
       $params = WhmcsParamsHelper::getWhmcsParamsByServerId($severId);
       return self::adminFromParams($params);
    }

    public static function adminFromParams(array $params)
    {
        $configuration = new ServerConfiguration((int)$params['serverid']);
        $params = array_merge($params, $configuration->get());

        return Tenant::WHMCSFactory($params, true, $params['tenantId']);
    }

    public static function getTenantAsUser(array $params, string $tenantId)
    {
        $configuration = new ServerConfiguration((int)$params['serverid']);
        $params = array_merge($params, $configuration->get());

        $tenant = Tenant::WHMCSFactory($params, false, $params['tenantId']);
        $tenant->setTenant($tenantId);

        return $tenant;
    }

    public static function getTenantFromServiceId(int $serviceId, bool $isAdmin = false)
    {
        $params = WhmcsParamsHelper::getWhmcsParamsByHostingId($serviceId);
        $configuration = new ServerConfiguration((int)$params['serverid']);
        $params = array_merge($params, $configuration->get());

        return Tenant::WHMCSFactory($params, $isAdmin, $params['tenantId']);
    }
}
