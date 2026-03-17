<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Database\Patches\Patches;

use Illuminate\Database\Capsule\Manager as DB;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Database\Patches\Source\BasePatch;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\Job;

class CustomIdColumnRename extends BasePatch
{
    public function execute():void
    {
        $jobModel = new Job();
        $builder  = $jobModel->getConnection()->getSchemaBuilder();

        if ($builder->hasColumn($jobModel->getTable(), 'custom_id'))
        {
            $statement = "ALTER TABLE {$jobModel->getTable()} CHANGE COLUMN custom_id rel_custom VARCHAR(32)";
            DB::statement($statement);
        }
    }
}