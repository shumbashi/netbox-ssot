<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums;

class LoggerMessages
{
    const EXCEPTION = 'exception';
    const UNABLE_TO_STORE_FILE = 'unable_to_store_file';
    const LIST_IMAGES_FAILED     = 'list_images_failed';
    const INSTANCE_START_SUCCESS = 'instance_start_success';
    const INSTANCE_START_FAILED = 'instance_start_failed';
    const INSTANCE_STOP_SUCCESS = 'instance_stop_success';
    const INSTANCE_STOP_FAILED = 'instance_stop_failed';
    const INSTANCE_HARD_REBOOT_SUCCESS = 'instance_hard_reboot_success';
    const INSTANCE_HARD_REBOOT_FAILED = 'instance_hard_reboot_failed';
    const INSTANCE_REINSTALL_SUCCESS = 'instance_reinstall_success';
    const INSTANCE_REINSTALL_FAILED = 'instance_reinstall_failed';
    const INSTANCE_PAUSE_SUCCESS = 'instance_pause_success';
    const INSTANCE_PAUSE_FAILED = 'instance_pause_failed';
    const INSTANCE_UNPAUSE_SUCCESS = 'instance_unpause_success';
    const INSTANCE_UNPAUSE_FAILED = 'instance_unpause_failed';
    const INSTANCE_SOFT_REBOOT_SUCCESS = 'instance_soft_reboot_success';
    const INSTANCE_SOFT_REBOOT_FAILED = 'instance_soft_reboot_failed';
    const INSTANCE_RESUME_SUCCESS = 'instance_resume_success';
    const INSTANCE_RESUME_FAILED = 'instance_resume_failed';
}