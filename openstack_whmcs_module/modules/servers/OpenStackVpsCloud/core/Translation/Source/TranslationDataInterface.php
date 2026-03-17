<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Translation\Source;

interface TranslationDataInterface
{
    public function getKey(): string;
    public function getReplacements(array $additions = []):array;
}