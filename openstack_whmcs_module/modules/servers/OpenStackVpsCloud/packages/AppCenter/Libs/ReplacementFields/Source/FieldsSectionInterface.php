<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Source;

interface FieldsSectionInterface
{
    public function loadColumns(): self;
    public function getColumns(): array;
}