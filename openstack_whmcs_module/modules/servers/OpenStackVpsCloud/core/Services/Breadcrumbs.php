<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Services;

use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Routing\Url;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Breadcrumbs\Item;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;

class Breadcrumbs extends \ModulesGarden\OpenStackVpsCloud\Core\UI\Breadcrumbs\Breadcrumbs
{
    public function __construct()
    {
        $this->addDefault();
    }

    protected function addDefault()
    {
        $route = \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Router::getCurrentRoute();
        if (!$route)
        {
            return;
        }

        $level = ModuleConstants::getLevel();
        $this->add(new Item(Translator::get($level . '.breadcrumbs.' . $route->getName()), Url::route($route->getName())));

        if ($route->getAction() && $route->getAction() != ModuleConstants::DEFAULT_CONTROLLER_ACTION)
        {
            $this->add(new Item(Translator::get($level . '.breadcrumbs.' . $route->getName() . '_' . $route->getAction()), Url::route($route->getName() . '@' . $route->getAction(), Request::query()->all())));
        }
    }
}
