<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Listeners;

use ModulesGarden\OpenStackVpsCloud\Core\Database\FileLoader;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Listener;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Database\PatchManager;

class ModuleActivated extends Listener
{
    public function handle($payload = []):void
    {
        (new FileLoader())->performQueryFromFile(ModuleConstants::getFullPath('packages', 'EasyTranslator', 'resources', 'database', 'schema.sql'));
        (new PatchManager())->executeAll();
    }
}