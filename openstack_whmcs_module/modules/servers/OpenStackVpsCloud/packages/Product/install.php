<?php

use ModulesGarden\OpenStackVpsCloud\Core\Hook\HookManager;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductDuplicate;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ConfigOptionsLoaded;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ModuleActivated;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ModuleUpgraded;
use function ModulesGarden\OpenStackVpsCloud\Core\listen;

return [
    'bootstrap' => function() {
        HookManager::create(__DIR__, true);
        ProductDuplicate::checkAndInitDuplicateProcess();

        listen(ModuleActivated::class,\ModulesGarden\OpenStackVpsCloud\Packages\Product\Listeners\ModuleActivated::class);
        listen(ModuleUpgraded::class,\ModulesGarden\OpenStackVpsCloud\Packages\Product\Listeners\ModuleUpgraded::class);
        listen(ConfigOptionsLoaded::class,\ModulesGarden\OpenStackVpsCloud\Packages\Product\Listeners\ConfigOptionsLoaded::class);

        //Register Configuration Service
        \ModulesGarden\OpenStackVpsCloud\Core\DependencyInjection\Container::getInstance()
            ->singleton(\ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\Configuration::class);
    },
    'packages'    => [
        'ModuleSettings',
    ],
];
