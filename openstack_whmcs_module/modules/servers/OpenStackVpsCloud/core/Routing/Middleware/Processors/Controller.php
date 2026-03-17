<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Routing\Middleware\Processors;

use ModulesGarden\OpenStackVpsCloud\Core\Http\Request;
use ModulesGarden\OpenStackVpsCloud\Core\Routing\Route;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;

class Controller
{
    public function run(Route $route, Request $request, callable $caller)
    {
        $next = fn($request) => $caller();

        foreach (Config::get('middlewares', []) as $middleware)
        {
            $next = fn($request) => $middleware($request, $next);
        }

        return $next($request);
    }
}