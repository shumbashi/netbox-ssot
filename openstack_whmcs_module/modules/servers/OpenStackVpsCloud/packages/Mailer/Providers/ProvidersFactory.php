<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Providers;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\MailBoxConfiguration\MailBoxConfiguration;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Providers\Source\AbstractProvider;

class ProvidersFactory
{
    const PROVIDERS_DIR = 'Providers';

    public static function byName(string $name, MailBoxConfiguration $configParams):AbstractProvider
    {
        $className = __NAMESPACE__ . '\\' .self::PROVIDERS_DIR . '\\' . $name;

        if (!class_exists($className))
        {
            throw new \Exception("Provider not found. Provided provider: {$name}");
        }

        $providerObject = new $className($configParams);

        if (!is_a($providerObject,AbstractProvider::Class ))
        {
            throw new \Exception('Invalid provider found. Provider must be instance of AbstractProvider');
        }

        return $providerObject;
    }
}