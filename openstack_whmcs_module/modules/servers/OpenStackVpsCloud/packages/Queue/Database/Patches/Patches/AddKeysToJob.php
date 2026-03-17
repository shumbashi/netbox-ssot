<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Database\Patches\Patches;

use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Database\Patches\Source\BasePatch;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\Job;
use \Illuminate\Database\Capsule\Manager as DB;

class AddKeysToJob extends BasePatch
{
    public function execute():void
    {
        $keys = ['rel_id', 'rel_type', 'rel_custom', 'status'];

        $jobTable = (new Job())->getTable();

        $existKeys = array_map(function($row) {
            return $row->Key_name;
        }, DB::select(DB::raw("SHOW KEYS FROM {$jobTable}")));

        $keys  = array_filter($keys, function($key) use ($existKeys){
            return !in_array($key, $existKeys);
        });

        foreach ($keys as $key)
        {
            $statement = "CREATE INDEX {$key} ON {$jobTable} ({$key})";

            DB::statement($statement);
        }
    }


    public function requires(): array
    {
        return [CustomIdColumnRename::class];
    }
}