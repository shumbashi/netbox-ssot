<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums;

class ConfigSettings
{
    const DISABLE_GUIDE              = 'queue.disable_guide';
    const DISABLE_CRON_INFO_HINT     = 'queue.disable_cron_job_hint';
    const CRON_MINUTES_CYCLE         = 'queue.cron_minutes_cycle';
    const JOBS_PRIORITY_VALUES       = 'queue.tasks';
    const JOB_ADDITIONAL_INFORMATION = 'queue.job_additional_information';
}