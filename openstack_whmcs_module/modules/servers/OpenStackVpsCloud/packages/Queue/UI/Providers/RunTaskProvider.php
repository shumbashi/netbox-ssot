<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Exceptions\RunTaskException;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Manager;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\Job;

class RunTaskProvider extends CrudProvider
{
    const ACTION_RUN = 'run';

    public function run()
    {
        try {
            $task    = Job::findOrFail($this->formData['id']);
            $manager = new Manager($task);
            $manager->fire();
        } catch (RunTaskException $ex) {
            throw new \Exception("runTaskFailedSeeLogs");
        }

    }
}