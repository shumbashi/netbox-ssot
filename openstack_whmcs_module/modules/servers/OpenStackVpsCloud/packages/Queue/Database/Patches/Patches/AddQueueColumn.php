<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Database\Patches\Patches;

use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Database\Patches\Source\BasePatch;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\Job;
use Illuminate\Database\Capsule\Manager as DB;

class AddQueueColumn extends BasePatch
{
    public function execute():void
    {
        $jobModel = new Job();
        $builder = $jobModel->getConnection()->getSchemaBuilder();

        if ($builder->hasColumn($jobModel->getTable(), 'queue'))
        {
            return;
        }

        $statement = "ALTER TABLE {$jobModel->getTable()} ADD `queue` VARCHAR (32) DEFAULT 'default' AFTER `data`";
        DB::statement($statement);
    }
}