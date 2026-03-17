<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Services;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\ServiceConfigOption;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Helpers\ConfigurableOptionHelper;

class ServiceConfigurableOptions
{
    protected int $serviceId;
    protected $configOptions = null;

    public function __construct(int $serviceId)
    {
        $this->serviceId = $serviceId;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $this->loadConfigOptions();

        $configOptions = [];

        foreach ($this->configOptions as $option)
        {
            if (!$option->optionid)
            {
                continue;
            }

            $configOptions[ConfigurableOptionHelper::parseConfigOptionName($option->configOption->optionname)] =
                $option->configOption->optiontype == 4 ? $option->qty : ConfigurableOptionHelper::parseConfigOptionName($option->subOption->optionname);
        }

        return $configOptions;
    }

    protected function loadConfigOptions()
    {
        if ($this->configOptions === null)
        {
            $this->configOptions = ServiceConfigOption::where('relid', $this->serviceId)->get();
        }
    }
}