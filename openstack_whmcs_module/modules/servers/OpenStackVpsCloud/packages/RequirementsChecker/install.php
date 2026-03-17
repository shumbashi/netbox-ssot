<?php

use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checker;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ModuleActivated;
use function ModulesGarden\OpenStackVpsCloud\Core\listen;

return [
    'bootstrap' => function(){

        listen(ModuleActivated::class, \ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Listeners\ModuleActivated::class);

        (new Checker())->check();
    },
];
