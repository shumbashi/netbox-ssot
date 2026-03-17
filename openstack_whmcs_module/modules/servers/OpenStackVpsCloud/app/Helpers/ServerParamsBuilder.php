<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Helpers;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Server;

class ServerParamsBuilder
{
    public static function fromFormData(array $formData, int $serverId = null)
    {
        $params = [
            'server' => true,
            'serverid' => (int)$serverId,
            'serverip' => Arr::get($formData, 'ipaddress', ''),
            'serverhostname' => Arr::get($formData, 'hostname', ''),
            'serverusername' => Arr::get($formData, 'username', ''),
            'serverpassword' => Arr::get($formData, 'password', ''),
            'serverport' => Arr::get($formData, 'port', 0),
            'serversecure' => Arr::get($formData, 'secure', 'off'),
            'serverhttpprefix' => Arr::get($formData, 'secure', 'off') === 'on' ? 'https' : 'http',
        ];

        if ($serverId)
        {
            $server = Server::select('password')->find($serverId);
            if (preg_match('/^\*+$/', $params['serverpassword'])) {
                $params['serverpassword'] = \decrypt($server->password ?: '');
            }
        }

        return $params;
    }
}