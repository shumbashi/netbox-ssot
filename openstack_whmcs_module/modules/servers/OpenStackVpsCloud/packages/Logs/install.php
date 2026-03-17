<?php

use ModulesGarden\OpenStackVpsCloud\Core\DependencyInjection\Container;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Listeners\ModuleActivated;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Listeners\ModuleUpgraded;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Services\Logs;
use function ModulesGarden\OpenStackVpsCloud\Core\listen;

return [
    'menu'        => [
        'admin'  => [
            'logs' => [
                'icon' => 'clipboard-text',
            ],
        ],
        'client' => [

        ],
    ],
    'controllers' => [
        'admin'  => [
            \ModulesGarden\OpenStackVpsCloud\Packages\Logs\Http\Admin\Logs::class,
        ],
        'client' => [

        ],
    ],
    'packages'    => [
        'ModuleSettings'
    ],
    'bootstrap'   => function() {
//        $oClass = new \ReflectionClass(\ModulesGarden\OpenStackVpsCloud\Packages\Logs\Enums\LogTypes::class);
//        foreach ($oClass->getConstants() as $type)
//        {
//            call_user_func([\ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Facades\Logger::class, $type], $type. '{adawd}', ['adawd' => $type]);
//        }

        \ModulesGarden\OpenStackVpsCloud\Core\Hook\HookManager::create(__DIR__, true);
        Container::getInstance()->singleton(Logs::class);
        listen(\ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ModuleActivated::class, ModuleActivated::class);
        listen(\ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ModuleUpgraded::class, ModuleUpgraded::class);
    },
];
