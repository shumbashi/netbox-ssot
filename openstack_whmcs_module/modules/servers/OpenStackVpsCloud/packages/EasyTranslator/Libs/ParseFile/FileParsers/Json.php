<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Libs\ParseFile\FileParsers;

use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Libs\ParseFile\Source\ParserInterface;

class Json implements ParserInterface
{
    public function toArray($content): array
    {
        return (array)json_decode($content);
    }
}