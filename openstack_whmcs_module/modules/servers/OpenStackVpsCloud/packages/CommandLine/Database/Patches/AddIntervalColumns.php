<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\CommandLine\Database\Patches;

use ModulesGarden\OpenStackVpsCloud\Core\Packages\Database\BasePatch;
use ModulesGarden\OpenStackVpsCloud\Packages\CommandLine\Models\Commands;
use \Illuminate\Database\Capsule\Manager as DB;

class AddIntervalColumns extends BasePatch
{
    public function execute()
    {
        $commandsModel = new Commands();
        $builder  = $commandsModel->getConnection()->getSchemaBuilder();

        $commandsTable = $commandsModel->getTable();

        if (!$builder->hasColumn($commandsTable, 'intervalType'))
        {
            $statement = "ALTER TABLE {$commandsTable} ADD `intervalType` ENUM('disabled', 'default', 'predefined', 'cron') DEFAULT 'default' AFTER params";

            DB::statement($statement);
        }

        if (!$builder->hasColumn($commandsTable, 'interval'))
        {
            $statement = "ALTER TABLE {$commandsTable} ADD `interval` VARCHAR(255) NULL AFTER intervalType";

            DB::statement($statement);
        }
    }
}