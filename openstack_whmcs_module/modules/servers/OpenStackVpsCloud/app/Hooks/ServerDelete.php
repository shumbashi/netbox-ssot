<?php

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ServerConfig;
use ModulesGarden\OpenStackVpsCloud\App\Models\Servers;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;

$hookManager->register(
    function ($args) {
        if (empty($args['serverid'])) {
            return;
        }

        $server = Servers::findOrFail($args['serverid']);
        if ($server->type != ModuleConstants::getModuleName()) {
            return;
        }

        $serverConfigManager = new ServerConfig();
        $serverConfigManager->deleteByServerId((int)$args['serverid']);
    },
    100
);
