<?php

return [
    'packages'    => function() {
        return \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('mailer.moduleLogging', false) ? ['Logs'] : [];
    },
    'bootstrap' => function(){
    },
];