<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\CommandLine\Database\Patches;

use ModulesGarden\OpenStackVpsCloud\Core\Packages\Database\BasePatch;
use ModulesGarden\OpenStackVpsCloud\Packages\CommandLine\Models\Commands;
use \Illuminate\Database\Capsule\Manager as DB;

class AddPendingStatus extends BasePatch
{
    public function execute()
    {
        $commandsModel = new Commands();

        $commandsTable = $commandsModel->getTable();

        $statuses = "'" . Commands::STATUS_STOPPED . "', ";
        $statuses .= "'" . Commands::STATUS_PENDING . "', ";
        $statuses .= "'" . Commands::STATUS_RUNNING . "', ";
        $statuses .= "'" . Commands::STATUS_ERROR . "'";

        $statement = "ALTER TABLE {$commandsTable} MODIFY COLUMN `status` ENUM({$statuses}) DEFAULT '" . Commands::STATUS_STOPPED . "' AFTER params";

        DB::statement($statement);
    }
}