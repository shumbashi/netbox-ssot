<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Services;

use ModulesGarden\OpenStackVpsCloud\Packages\ModuleSettings\Support\Facades\ModuleSettings;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\ModuleSettings as Settings;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\Job;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\JobLog;
use WHMCS\Database\Capsule as DB;

class AutoPrune
{
    public function run()
    {
        if (ModuleSettings::get(Settings::AUTO_PRUNE))
        {
            $olderThanDays = (int)ModuleSettings::get(Settings::AUTO_PRUNE_OLDER_THAN);

            $tj = (new Job())->getTable();
            JobLog::join($tj, 'job_id', '=', $tj . '.id')
                ->where($tj . '.created_at', '<', DB::raw('DATE_SUB(NOW(), INTERVAL ' . $olderThanDays . ' day)'))
                ->delete();

            Job::where('created_at', '<', DB::raw('DATE_SUB(NOW(), INTERVAL ' . $olderThanDays . ' day)'))->delete();
        }
    }
}