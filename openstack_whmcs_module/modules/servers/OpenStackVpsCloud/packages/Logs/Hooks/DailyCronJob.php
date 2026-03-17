<?php

$hookManager->register(
    function($args) {
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Logs\Services\AutoPrune())->run();
    },
    100
);
