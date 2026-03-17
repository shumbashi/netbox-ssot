<?php

use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Services\AutoPrune;

$hookManager->register(
    function($args) {
        (new AutoPrune())->run();
    },
    100
);