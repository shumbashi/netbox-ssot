<?php

use ModulesGarden\OpenStackVpsCloud\App\Events\MyTestEvent;
use ModulesGarden\OpenStackVpsCloud\App\Listeners\MyTestListener;

return [
    MyTestEvent::class => [
        MyTestListener::class,
    ],
];
