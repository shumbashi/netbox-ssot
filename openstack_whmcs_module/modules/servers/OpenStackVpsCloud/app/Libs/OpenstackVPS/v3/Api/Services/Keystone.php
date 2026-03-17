<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services;

use Exception;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\AbstractService;

class Keystone extends AbstractService
{

    const USEENDPOINT = 'identity';
    const VERSION     = 'v2.0';
    const PORT        = 5000;

    function getVersion()
    {

        return $this->call($this->_endPoint);
    }

    private function call($endpoint, $request = [], $method = 'GET')
    {

        $ch  = curl_init();
        $url = $this->url . $endpoint;
        if ($method == 'GET' && !empty($request))
        {
            $request = http_build_query($request);
            $url     .= '?' . $request;
        }

        curl_setopt($ch, CURLOPT_URL, $url);

        switch ($method)
        {
            case 'GET':
                # Nothing extra to do.
                break;
            case 'POST':
                $request = json_encode($request);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
                break;

            case 'PUT':
                $request = json_encode($request);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
                break;

            case "DELETE":
                $request = json_encode($request);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
                break;
            case 'PATCH':
                $request = json_encode($request);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
                break;
            default:
                throw new Exception('Invalid HTTP Method specified');
                return false;
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $data     = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($data === false)
        {
            $err = ucwords(curl_error($ch)) ? ucwords(curl_error($ch)) : "Unable connect to: " . $url;
            curl_close($ch);
            throw new Exception("CURL Error: " . $err, 0);
        }
        curl_close($ch);
        $response = json_decode($data);
        return $response;
    }

}
