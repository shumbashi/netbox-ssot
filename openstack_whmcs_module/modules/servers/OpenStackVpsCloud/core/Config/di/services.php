<?php

use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Addon\Config;
use ModulesGarden\OpenStackVpsCloud\Core\DependencyInjection\PackageServices;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Dispatcher;
use ModulesGarden\OpenStackVpsCloud\Core\Lang\Lang;
use ModulesGarden\OpenStackVpsCloud\Core\Services\Breadcrumbs;
use ModulesGarden\OpenStackVpsCloud\Core\Services\Messages;
use ModulesGarden\OpenStackVpsCloud\Core\Services\Route;
use ModulesGarden\OpenStackVpsCloud\Core\Services\Translator;
use ModulesGarden\OpenStackVpsCloud\Core\Services\Validator;
use ModulesGarden\OpenStackVpsCloud\Core\Session\Session;

return [
    Dispatcher::class,

    //New
    Translator::class,
    Validator::class,
    PackageServices::class,
    \ModulesGarden\OpenStackVpsCloud\Core\Services\Params::class,
    Session::class,
    Breadcrumbs::class,
    Messages::class,
    \ModulesGarden\OpenStackVpsCloud\Core\Services\Router::class,
    \ModulesGarden\OpenStackVpsCloud\Core\Services\Smarty::class,
    \ModulesGarden\OpenStackVpsCloud\Core\Services\Request::class,
    \ModulesGarden\OpenStackVpsCloud\Core\Services\Config::class,
    \ModulesGarden\OpenStackVpsCloud\Core\Services\Binder::class,
    \ModulesGarden\OpenStackVpsCloud\Core\Services\Menu::class,
    Lang::class,
    \ModulesGarden\OpenStackVpsCloud\Core\Services\Store::class
];
