<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Database\Patches;

use ModulesGarden\OpenStackVpsCloud\Core\Packages\Database\BasePatch;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\DynamicTranslation;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\Lang;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\MissingLang;
use \Illuminate\Database\Capsule\Manager as DB;

class AddDefaultTimestamps extends BasePatch
{
    public function execute():void
    {
        $this->addDefaultCurrentTimestamp((new Lang())->getTable(), "created_at");
        $this->addDefaultCurrentTimestamp((new DynamicTranslation())->getTable(), "created_at");
        $this->addDefaultCurrentTimestamp((new MissingLang())->getTable(), "created_at");
    }

    protected function addDefaultCurrentTimestamp(string $table, string $column):void
    {
        DB::statement("ALTER TABLE {$table} MODIFY COLUMN {$column} TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    }
}