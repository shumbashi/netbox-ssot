<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Traits;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;

/*
 * @property \ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api $api
 */
trait ApiTrait
{
    public function __get(string $name)
    {
       if ($name == 'api') {
           if (!Api::getInstance()->getApIdentity()) {
               Factory::getTenantFromServiceId(Params::get('serviceid'));
           }

           return Api::getInstance();
       }

       return $this->$name;
    }
}