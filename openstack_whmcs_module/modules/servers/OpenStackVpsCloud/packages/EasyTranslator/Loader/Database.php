<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Loader;

use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Repositories\Langs;

class Database extends \Symfony\Component\Translation\Loader\ArrayLoader implements \Symfony\Component\Translation\Loader\LoaderInterface
{
    public function load($resource, $locale, $domain = "")
    {
        return parent::load(Langs::getCombined($locale), $locale, $domain);
    }
}
