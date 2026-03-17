<?php

use ModulesGarden\OpenStackVpsCloud\Core\DependencyInjection;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ConfigOptionsLoaded;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ModuleActivated;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ModuleUpgraded;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Commands\Queue;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Commands\QueuePrune;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\DatabaseQueue;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Helpers\TimezoneChecker;
use function ModulesGarden\OpenStackVpsCloud\Core\listen;

return [
    'menu'        => [
        'admin'  => [
            'queue' => [
                'icon' => 'format-list-bulleted',
            ],
        ],
        'client' => [

        ],
    ],
    'controllers' => [
        'admin'  => [
            \ModulesGarden\OpenStackVpsCloud\Packages\Queue\Http\Admin\Queue::class,
        ],
        'client' => [

        ],
    ],
    'packages'    => [
        'ModuleSettings',
        'CommandLine'
    ],
    'commands'    => [
        Queue::class,
        QueuePrune::class
        //QueueLoop::class, //TODO fix in CommandLine package: issue #1
    ],
    'bootstrap'   => function() {
        \ModulesGarden\OpenStackVpsCloud\Core\Hook\HookManager::create(__DIR__, true);

        listen(ModuleActivated::class, \ModulesGarden\OpenStackVpsCloud\Packages\Queue\Listeners\ModuleActivated::class);
        listen(ModuleUpgraded::class, \ModulesGarden\OpenStackVpsCloud\Packages\Queue\Listeners\ModuleUpgraded::class);
        listen(ConfigOptionsLoaded::class, \ModulesGarden\OpenStackVpsCloud\Packages\Queue\Listeners\ConfigOptionsLoaded::class);

        /**
         * Set queue resolver
         */
        \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Dispatcher::setQueueResolver(function() {
            return DependencyInjection::create(DatabaseQueue::class);
        });

        try
        {
            TimezoneChecker::check();
        }
        catch (Exception $e)
        {
            //Do nothing
        }
    },
];
