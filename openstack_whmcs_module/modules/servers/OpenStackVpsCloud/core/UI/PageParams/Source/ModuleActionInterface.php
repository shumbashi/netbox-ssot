<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\UI\PageParams\Source;

interface ModuleActionInterface
{
    public function selectAppropriateParameters(array $params): array;
}