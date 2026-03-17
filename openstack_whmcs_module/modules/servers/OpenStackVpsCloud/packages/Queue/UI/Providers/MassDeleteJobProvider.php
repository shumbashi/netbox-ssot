<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\Job;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\JobLog;

class MassDeleteJobProvider extends CrudProvider
{
    public function delete()
    {
        $ids = explode(',', $this->formData['id']);
        Job::whereIn('id', $ids)->delete();
        JobLog::whereIn('job_id', $ids)->delete();
    }
}
