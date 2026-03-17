<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Addon;

use ModulesGarden\OpenStackVpsCloud\Core\Configuration\Data;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AddonControllerInterface;

/**
 * Module configuration wrapper
 */
class Config extends \ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\AddonController implements AddonControllerInterface
{
    public function execute($params = [])
    {
        return [
            'name'        => \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('configuration.systemName'),
            'description' => \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('configuration.description'),
            'version'     => \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('configuration.version'),
            'author'      => \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('configuration.author'),
            'fields'      => \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('configuration.fields', [])
        ];
    }
}
