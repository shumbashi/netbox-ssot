<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Loader;

use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Repositories\DynamicTranslations as DynamicTranslationsRepository;

class DynamicTranslations extends \Symfony\Component\Translation\Loader\ArrayLoader implements \Symfony\Component\Translation\Loader\LoaderInterface
{
    public const DYNAMICS_DOMAIN = "dynamics";

    public function load($resource, string $locale, string $domain = '')
    {
        return parent::load(DynamicTranslationsRepository::getCombined(), $locale, self::DYNAMICS_DOMAIN);
    }
}