<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Database\Patches;

use ModulesGarden\OpenStackVpsCloud\Core\Packages\Database\BasePatch;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\MissingLang;
use \Illuminate\Database\Capsule\Manager as DB;

class AddSourceTextColumn extends BasePatch
{
    public function execute():void
    {
        $missingLangModel = new MissingLang();
        $builder = $missingLangModel->getConnection()->getSchemaBuilder();

        if (!$builder->hasColumn($missingLangModel->getTable(), 'source'))
        {
            DB::statement("ALTER TABLE {$missingLangModel->getTable()} ADD `source` VARCHAR(255) DEFAULT NULL AFTER lang");
        }
    }
}