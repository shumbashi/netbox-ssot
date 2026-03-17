<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Routing\Middleware;

use ModulesGarden\OpenStackVpsCloud\Core\Http\Request;

abstract class AbstractMiddleware
{
    public function __invoke(Request $request, \Closure $next)/*: \ModulesGarden\OpenStackVpsCloud\Core\Http\Response*/
    {
        return $next($request);
    }
}