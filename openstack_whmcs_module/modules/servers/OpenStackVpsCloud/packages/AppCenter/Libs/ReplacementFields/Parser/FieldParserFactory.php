<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Parser;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Builder\ServiceFieldsParserBuilder;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Source\FieldsBuilderInterface;

class FieldParserFactory
{
    public static function forService(Service $service): ?FieldParser
    {
        $className = Config::get('appCenter.RelatedFieldsBuilder', null);
        if (!$className) {
            $className = ServiceFieldsParserBuilder::class;
        }

        if (!class_exists($className)) {
            throw new \Exception(sprintf('Class %s not found', $className));
        }

        $builder = new $className();
        if (!($builder instanceof FieldsBuilderInterface)) {
            throw new \Exception(sprintf('Class %s should implement %s', $className, FieldsBuilderInterface::class));
        }

        $builder->setService($service);
        $builder->build();
        return $builder->getParser();
    }
}