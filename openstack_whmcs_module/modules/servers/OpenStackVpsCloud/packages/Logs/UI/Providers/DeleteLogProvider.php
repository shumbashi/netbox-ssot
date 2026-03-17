<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Models\Logs;

class DeleteLogProvider extends CrudProvider
{
    public function delete()
    {
        if (!Config::get('logs.delete_logs.enabled', true))
        {
            throw new \Exception('deletingLogsIsNotAllowed');
        }

        Logs::whereIn('id', explode(',',  $this->formData->get('id')))
            ->delete();
    }
}
