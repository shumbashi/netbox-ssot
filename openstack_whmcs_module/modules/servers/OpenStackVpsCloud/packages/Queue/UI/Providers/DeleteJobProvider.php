<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\JobNameTranslator;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\Job;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\JobLog;

class DeleteJobProvider extends CrudProvider
{
    use TranslatorTrait;

    public function delete()
    {
        $jobId = $this->formData->get('id', 0);
        $jobName = Job::where('id', $jobId)->first()->job;

        Job::where('id', $jobId)->delete();
        JobLog::where('job_id', $jobId)->delete();

        return (new Response())
            ->setActions([Action::reloadParent()])
            ->setSuccess($this->translate('delete_success', ['name' => (new JobNameTranslator())->format($jobName)]));
    }
}