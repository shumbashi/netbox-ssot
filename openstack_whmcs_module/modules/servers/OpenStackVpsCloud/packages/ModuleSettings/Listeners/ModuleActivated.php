<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\ModuleSettings\Listeners;

use ModulesGarden\OpenStackVpsCloud\Core\Database\FileLoader;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Listener;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;

class ModuleActivated extends Listener
{
    public function handle($payload = [])
    {
        (new FileLoader())
            ->performQueryFromFile(ModuleConstants::getFullPath('packages', 'ModuleSettings', 'Database', 'Schema.sql'));
    }
}
