<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Services;

use ModulesGarden\OpenStackVpsCloud\Core\Helper\BuildUrl;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Smarty;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Password;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Enums\ConfigSettings;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\Configuration\ConfigurationContainer;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Support\Factories\ConfigurationFactory;

class HostnameGenerateService
{
    const HOSTNAME_FORMAT_FIELD         = "hostnameFormat";
    const HOSTNAME_FORMAT_PLACEHOLDER   = 'client-{$clientId}-service-{$serviceId}-{$rand}';
    const OVERRIDE_DOMAIN_FIELD         = "overrideDomain";
    const NEXT_INCREMENTAL_VALUE_FIELD  = "nextIncrementalValue";
    const RANDOM_PARAM_LENGTH_FIELD     = "randomLength";
    const RANDOM_PARAM_CHARS_FIELD      = "randomChars";
    const RANDOM_PARAM_DEFAULT_CHARS    = "abcdefghijklmnopqrstuvwxyz0123456789";
    const RANDOM_PARAM_DEFAULT_LENGTH   = 10;

    protected ConfigurationContainer $configuration;
    protected Service $service;

    public function __construct(Service $service)
    {
        $this->service       = $service;
        $this->configuration = ConfigurationFactory::fromProduct($service->product->id);
    }

    public function generate():?string
    {
        try
        {
            $this->check();

            $result = Smarty::fetch($this->getFormat(), $this->getSmartyValues());

            if (!empty($result))
            {
                $this->incrementNextIncrementalValue();
            }

            return $result;
        }
        catch (\Exception $e)
        {
            return null;
        }
    }

    protected function incrementNextIncrementalValue():void
    {
        $nextIncrementalValue = $this->configuration->getProductSetting(self::NEXT_INCREMENTAL_VALUE_FIELD);
        $nextIncrementalValue = is_numeric($nextIncrementalValue) ? $nextIncrementalValue : 0;

        $productConfiguration = new ProductConfiguration($this->service->product->id);

        $productConfiguration->save([self::NEXT_INCREMENTAL_VALUE_FIELD => ++$nextIncrementalValue]);
    }

    protected function check():void
    {
        if (empty($this->configuration->getProductSetting(self::HOSTNAME_FORMAT_FIELD)))
        {
            throw new \Exception("Hostname format is required");
        }

        if (!empty($this->service->domain) && !$this->configuration->getProductSetting(self::OVERRIDE_DOMAIN_FIELD, true))
        {
            throw new \Exception("Domain already exists");
        }
    }

    protected function getFormat():string
    {
        return $this->configuration->getProductSetting(self::HOSTNAME_FORMAT_FIELD);
    }

    protected function getSmartyValues():array
    {
        $nextIncrementalValue = $this->configuration->getProductSetting(self::NEXT_INCREMENTAL_VALUE_FIELD);

        return [
            'whmcsMainDomain'      => BuildUrl::getHost(),
            'clientId'             => $this->service->client->id,
            'serviceId'            => $this->service->id,
            'orderId'              => $this->service->order->id,
            'timestamp'            => $this->generateTimestamp(),
            'rand'                 => $this->generateRand(),
            'nextIncrementalValue' => is_numeric($nextIncrementalValue) ? $nextIncrementalValue : 0,
        ];
    }

    protected function generateRand():string
    {
        return Password::generate(
            $this->configuration->getProductSetting(self::RANDOM_PARAM_LENGTH_FIELD) ?:
                Config::get(ConfigSettings::HOSTNAME_GENERATOR_LENGTH, self::RANDOM_PARAM_DEFAULT_LENGTH),
            true,
            true,
            false,
            $this->configuration->getProductSetting(self::RANDOM_PARAM_CHARS_FIELD) ?:
                Config::get(ConfigSettings::HOSTNAME_GENERATOR_CHARS, self::RANDOM_PARAM_DEFAULT_CHARS)
        );
    }

    protected function generateTimestamp():string
    {
        return (new \DateTime())->getTimestamp();
    }
}