<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Database\Patches;

use ModulesGarden\OpenStackVpsCloud\Core\Packages\Database\BasePatch;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\Lang;
use \Illuminate\Database\Capsule\Manager as DB;

class AddDynamicColumn extends BasePatch
{
    public function execute():void
    {
        $langModel = new Lang();
        $builder = $langModel->getConnection()->getSchemaBuilder();

        if (!$builder->hasColumn($langModel->getTable(), 'dynamic'))
        {
            DB::statement("ALTER TABLE {$langModel->getTable()} ADD `dynamic` BOOLEAN DEFAULT FALSE AFTER value");
        }
    }
}