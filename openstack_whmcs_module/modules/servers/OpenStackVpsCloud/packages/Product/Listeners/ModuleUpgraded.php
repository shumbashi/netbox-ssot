<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Listeners;

use ModulesGarden\OpenStackVpsCloud\Core\Database\FileLoader;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Listener;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\LogActivity;

class ModuleUpgraded extends Listener
{
    public function handle($payload = [])
    {
        try
        {
            (new FileLoader())->performQueryFromFile(ModuleConstants::getFullPath('packages', 'Product', 'resources', 'database', 'schema.sql'));
        }
        catch (\Throwable $ex)
        {
            LogActivity::error($ex->getMessage());
        }
    }
}