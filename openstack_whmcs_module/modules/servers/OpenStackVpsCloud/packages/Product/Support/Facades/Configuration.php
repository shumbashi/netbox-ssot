<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Support\Facades;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\AbstractFacade;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\Configuration\ConfigurationContainer;

/**
 * @method static ConfigurationContainer getConfiguration()
 * @method static setConfiguration(ConfigurationContainer $configuration)
 */
class Configuration extends AbstractFacade
{
    protected static function getFacadeAccessor(): string
    {
        return \ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\Configuration::class;
    }
}