<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Helpers;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\CustomFieldValue;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Servers;

use WHMCS\Module\Server;

class WhmcsParamsHelper
{
    /**
     * @param int $serverID
     * @return array
     */
    public static function getWhmcsParamsByServerId(int $serverID)
    {
        $server = new \WHMCS\Module\Server();

        return $server->getServerParams($serverID);
    }

    public static function getWhmcsParamsByVmId(string $vmId)
    {
        $hostingId = CustomFieldValue::where('value', '=', $vmId)->first()->relid;

        return self::getWhmcsParamsByHostingId($hostingId);
    }

    /**
     * @param int $hostingId
     * @return mixed
     */
    public static function getWhmcsParamsByHostingId(int $hostingId)
    {
        $server = new \WHMCS\Module\Server();
        $server->setServiceId($hostingId);

        return $server->buildParams();
    }

    /**
     * @param array $params
     * @return array
     */
    public static function prepareParamsToSendInJob(array $params)
    {
        unset($params['templatevars']);
        unset($params['model']);

        return $params;
    }
}