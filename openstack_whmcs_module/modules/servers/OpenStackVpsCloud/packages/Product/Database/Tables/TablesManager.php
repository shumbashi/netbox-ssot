<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Database\Tables;

use ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\Update\PatchManager;
use ModulesGarden\OpenStackVpsCloud\Core\Database\FileLoader;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Packages\ModuleSettings\Support\Facades\ModuleSettings;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Database\Patches\PatchesManager as DataBasePatches;

class TablesManager
{
    public static function processSchemaQueries(): void
    {
        /*Run create table schema*/
        (new FileLoader())->performQueryFromFile(ModuleConstants::getFullPath('packages', 'Product', 'resources', 'database', 'schema.sql'));

        /*Run patches*/
        (new DataBasePatches())->executeAll();

        /*Run custom schema queries*/
        (new FileLoader())->performQueryFromFile(ModuleConstants::getFullPath('app', 'Database', 'schema.sql'));
        (new FileLoader())->performQueryFromFile(ModuleConstants::getFullPath('app', 'Database', 'data.sql'));
    }

    public static function processUpgradeQueries(): void
    {
        /* Run custom upgrade queries*/
        (new PatchManager(PatchManager::TYPE_SERVER))->run(ModuleSettings::get('server.version', 0));

        ModuleSettings::save(['server.version' => Config::get('configuration.version')]);
    }
}