<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\Factories\Parser;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\App;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Parser\FieldParserFactory;

class AppConfigParser
{
    const SMARTY_BRACES_REGEX = '/\{[^}]*\}/';

    public static function forServiceItem(?Service $service, ?App $app, array $overrideConfig = []): App
    {
        $serviceParser = FieldParserFactory::forService($service);

        $configReplacements = [];
        foreach ($app->getConfig() as &$config) {
            $overrideValue = Arr::get($overrideConfig, $config->getSetting());
            if (is_string($overrideValue) && $config->getVisible()) {
                /*Escape smarty tags*/
                $sanitizedValue = self::sanitize($overrideValue);
                $configReplacements[$config->getSetting()] = $sanitizedValue;
                $config->setValue($sanitizedValue);
                continue;
            }

            $configReplacements[$config->getSetting()] = new ParserItem($config, $serviceParser);
        }

        $serviceParser->addReplacement('config', $configReplacements);
        foreach ($app->getConfig() as &$config) {
            if (empty($config->getValue())) {
                continue;
            }

            $replacement = Arr::get($configReplacements, $config->getSetting());
            if (!($replacement instanceof ParserItem)) {
                continue;
            }

            if (is_array($config->getValue()))
            {
                $values = $config->getValue();
                foreach ($values as &$value)
                {
                    $value = $serviceParser->setTemplate($value)->parse();
                }
                $config->setValue($values);
            }
            else
            {
                $config->setValue($serviceParser->setTemplate($config->getValue())->parse());
            }

            ParserItem::resetHistory();
        }

        if (!empty($app->getDescription())) {
            $description = $serviceParser->setTemplate($app->getDescription())
                ->parse();

            ParserItem::resetHistory();
            $app->setDescription($description);
        }

        return $app;
    }

    public static function sanitize(string $value)
    {
        return preg_replace(self::SMARTY_BRACES_REGEX, '', $value);
    }
}