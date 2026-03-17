<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Libs\ParseFile;

use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Libs\ParseFile\Source\ParserInterface;

class ParsersFactory
{
    const PARSERS_DIR = 'FileParsers';

    public static function createFromFileExtension($fileExtension):ParserInterface
    {
        $parserName = ucfirst($fileExtension);
        $className = __NAMESPACE__.'\\'.self::PARSERS_DIR .'\\'.$parserName;

        if (!class_exists($className) ) {
            throw new \Exception('extensionParserNotFound');
        }

        $parserObject = new $className();

        if (!is_subclass_of($parserObject,ParserInterface::Class )) {
            throw new \Exception('invalidParserExtensionFound');
        }

        return $parserObject;
    }
}