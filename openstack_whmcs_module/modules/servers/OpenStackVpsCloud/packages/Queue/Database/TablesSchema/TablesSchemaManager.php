<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Database\TablesSchema;

use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Database\Patches\PatchesManager as DataBasePatches;
use ModulesGarden\OpenStackVpsCloud\Core\Database\FileLoader;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;

class TablesSchemaManager
{
    public function processSchemaQueries()
    {
        (new FileLoader())->performQueryFromFile(ModuleConstants::getFullPath('packages', 'Queue', 'resources', 'database', 'schema.sql'));
        (new DataBasePatches())->executeAll();
    }
}