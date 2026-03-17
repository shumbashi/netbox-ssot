<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Services;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\Configuration\ConfigurationContainer;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Support\Factories\ConfigurationFactory;

class Configuration
{
    protected static ConfigurationContainer $configurationContainer;

    public function __construct()
    {
        $this->load();
    }

    protected function load():void
    {
        static::$configurationContainer = ConfigurationFactory::fromParams(Params::all());
    }

    public function getConfiguration():ConfigurationContainer
    {
        return static::$configurationContainer;
    }

    public function setConfiguration(ConfigurationContainer $configuration):self
    {
        static::$configurationContainer = $configuration;

        return $this;
    }
}