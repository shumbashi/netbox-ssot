<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Listeners;

use ModulesGarden\OpenStackVpsCloud\Core\Events\Listener;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\LogActivity;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Database\Tables\TablesManager;
use Illuminate\Database\Capsule\Manager as DB;

class ConfigOptionsLoaded extends Listener
{
    public function handle($payload = [])
    {
        try
        {
            TablesManager::processSchemaQueries();
            TablesManager::processUpgradeQueries();
        }
        catch (\Throwable $ex)
        {
            LogActivity::error($ex->getMessage());
        }
    }
}
