<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Http\View\MenuProviders;

interface MenuProviderInterface
{
    public function getMenuItems(): array;
}
