<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\CommandLine\Listeners;

use ModulesGarden\OpenStackVpsCloud\Core\Database\FileLoader;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Listener;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Packages\CommandLine\Database\PatchesManager;

class ModuleActivated extends Listener
{
    public function handle($payload = [])
    {
        (new FileLoader())->performQueryFromFile(ModuleConstants::getFullPath('packages', 'CommandLine', 'resources', 'database', 'schema.sql'));
        (new PatchesManager())->executeAll();
    }
}
