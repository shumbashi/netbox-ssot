<?php

use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ModuleActivated;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ModuleUpgraded;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\PreServerConfigurationLoaded;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ConfigOptionsLoaded;
use function ModulesGarden\OpenStackVpsCloud\Core\listen;

return [
    'bootstrap' => function() {
        listen(ModuleActivated::class, \ModulesGarden\OpenStackVpsCloud\Packages\ModuleSettings\Listeners\ModuleActivated::class);
        listen(ModuleUpgraded::class, \ModulesGarden\OpenStackVpsCloud\Packages\ModuleSettings\Listeners\ModuleUpgraded::class);
        listen(PreServerConfigurationLoaded::class, \ModulesGarden\OpenStackVpsCloud\Packages\ModuleSettings\Listeners\PreServerConfigurationLoaded::class);
        listen(ConfigOptionsLoaded::class, \ModulesGarden\OpenStackVpsCloud\Packages\ModuleSettings\Listeners\ModuleActivated::class);

        \ModulesGarden\OpenStackVpsCloud\Core\DependencyInjection\Container::getInstance()->singleton(\ModulesGarden\OpenStackVpsCloud\Packages\ModuleSettings\Services\ModuleSettings::class);
    },
];
