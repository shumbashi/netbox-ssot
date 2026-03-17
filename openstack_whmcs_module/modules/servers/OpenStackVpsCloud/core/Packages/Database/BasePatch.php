<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Packages\Database;

abstract class BasePatch implements PatchInterface
{
    public function requires(): array
    {
        return [];
    }
}