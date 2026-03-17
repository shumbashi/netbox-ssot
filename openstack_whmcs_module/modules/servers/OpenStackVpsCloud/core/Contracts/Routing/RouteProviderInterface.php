<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Contracts\Routing;

use ModulesGarden\OpenStackVpsCloud\Core\Routing\Route;

interface RouteProviderInterface
{
    public function find(\Symfony\Component\HttpFoundation\Request $request, string $level) : ?Route;
}