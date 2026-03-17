<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Listeners;

use ModulesGarden\OpenStackVpsCloud\Core\Events\Listener;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Database\TablesSchema\TablesSchemaManager;

class ModuleActivated extends Listener
{
    public function handle($payload = [])
    {
        (new TablesSchemaManager())->processSchemaQueries();
    }
}
