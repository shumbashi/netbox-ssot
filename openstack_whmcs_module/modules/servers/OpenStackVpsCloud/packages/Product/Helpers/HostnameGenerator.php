<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Helpers;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\HostnameGenerateService;

class HostnameGenerator
{
    public static function generate(Service $service):?string
    {
        return (new HostnameGenerateService($service))->generate();
    }

    public static function generateById(int $serviceId):?string
    {
        return self::generate(Service::find($serviceId));
    }
}