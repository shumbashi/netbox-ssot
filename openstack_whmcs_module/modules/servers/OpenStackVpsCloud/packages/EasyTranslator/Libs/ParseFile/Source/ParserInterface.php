<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Libs\ParseFile\Source;

interface ParserInterface
{
    public function toArray($content): array;
}