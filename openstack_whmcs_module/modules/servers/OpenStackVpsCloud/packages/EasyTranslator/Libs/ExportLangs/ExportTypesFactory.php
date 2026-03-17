<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Libs\ExportLangs;

use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Libs\ExportLangs\Source\ExportTypeInterface;

class ExportTypesFactory
{
    const TYPES_DIR = 'Types';

    public static function getAll():array
    {
        $types = scandir(__DIR__ . DIRECTORY_SEPARATOR . self::TYPES_DIR);

        $types = array_filter($types, function($file) {
            return !in_array($file, ['.', '..']);
        });

        $typesObjects = [];

        foreach ($types as $type)
        {
            $typeClass = __NAMESPACE__ . "\\" . self::TYPES_DIR . "\\" .  str_replace('.php', '', $type);

            if (!class_exists($typeClass))
            {
                continue;
            }

            $typesObject = new $typeClass();

            if (!$typesObject instanceof ExportTypeInterface)
            {
                continue;
            }

            $typesObjects[] = $typesObject;
        }

        return $typesObjects;
    }

    public static function getAllNames():array
    {
        return array_map(function(ExportTypeInterface $type) {
            return $type->getName();
        }, self::getAll());
    }

    public static function findByType(string $typeName):ExportTypeInterface
    {
        $foundedTypes = array_filter(self::getAll(), function(ExportTypeInterface $type) use ($typeName) {
            return $type->getName() === $typeName;
        });

        if (empty($foundedTypes))
        {
            throw new \Exception("Type '$typeName' not found");
        }

        return current($foundedTypes);
    }
}