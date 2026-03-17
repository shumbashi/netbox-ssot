<?php

use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ModuleActivated;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ModuleUpgraded;
use function ModulesGarden\OpenStackVpsCloud\Core\listen;

return [
    'commands'  => [
    ],
    'bootstrap' => function() {
        listen(ModuleActivated::class, \ModulesGarden\OpenStackVpsCloud\Packages\CommandLine\Listeners\ModuleActivated::class);
        listen(ModuleUpgraded::class, \ModulesGarden\OpenStackVpsCloud\Packages\CommandLine\Listeners\ModuleUpgraded::class);
    },
];
