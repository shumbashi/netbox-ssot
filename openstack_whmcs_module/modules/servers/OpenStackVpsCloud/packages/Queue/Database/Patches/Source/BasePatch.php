<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Database\Patches\Source;

abstract class BasePatch implements PatchInterface
{
    public function requires(): array
    {
        return [];
    }
}