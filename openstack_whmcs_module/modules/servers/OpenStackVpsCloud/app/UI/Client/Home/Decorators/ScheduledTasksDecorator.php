<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Decorators;

class ScheduledTasksDecorator
{
    const TASK_BUILDING_VM = 'BuildingVM';
    const TASK_BUILDING_VOLUME = 'BuildingVolume';
    const TASK_CHANGING_PACKAGE = 'ChangingPackage';
    const TASK_CONFIRMING_RESIZE = 'ConfirmingResizeVm';
    const TASK_CREATION_INSTANCE = 'CreationInstance';
    const TASK_CREATION_VM = 'CreationVm';
    const TASK_CREATING_INTERFACES = 'CreateInterfaces';
    const TASK_DELETE_BACKUPS = 'DeleteBackups';
    const TASK_DELETE_BLOCK_DEVICES = 'DeleteBlockDevices';
    const TASK_DELETE_PORTS = 'DeletePorts';
    const TASK_DELETE_VOLUMES = 'DeleteVolumes';
    const TASK_DELETE_FLOATING_IPS = 'DeletingFloatingIPs';
    const TASK_DELETE_IP = 'DeletingIP';
    const TASK_EXTENDING_VOLUME = 'ExtendingVolume';
    const TASK_REBUILDING_VOLUME = 'RebuildingVolume';
    const TASK_RESTORING_VOLUME = 'RestoringVolume';
    const TASK_RESTORING_VOLUME_PROCESS = 'RestoringVolumeProcess';
    const TASK_SETTING_VM_DETAILS = 'SettingVMDetails';
    const TASK_SEND_EMAIL = 'SendEmail';
    const TASK_SCHEDULED_BACKUP = 'ScheduledBackups';
    const TASK_TERMINATION_ACCOUNT = 'TerminatingAccount';
    const TASK_TERMINATION_VM = 'TerminationVM';
    const TASK_DELETE_DETAILS = 'DeleteDetails';

    const BUILDING_VM_TASK_NAME = 'Building VM';
    const BUILDING_VOLUME_TASK_NAME = 'Building Volume';
    const CHANGING_PACKAGE_TASK_NAME = 'Changing Package';
    const CONFIRMING_RESIZE_TASK_NAME = 'Confirming Resize';
    const CREATION_VM_TASK_NAME = 'Creating Instance';
    const CREATION_ACCOUNT_TASK_NAME = 'Creating Account';
    const CREATING_INTERFACES_TASK_NAME = 'Creating Interfaces';
    const DELETE_BACKUPS_TASK_NAME = 'Deleting Backups';
    const DELETE_BLOCK_DEVICES_TASK_NAME = 'Deleting Block Devices';
    const DELETE_PORTS_TASK_NAME = 'Deleting Ports';
    const DELETE_VOLUMES_TASK_NAME = 'Deleting Volumes';
    const DELETE_FLOATING_IPS_TASK_NAME = 'Deleting Floating IPs';
    const DELETE_IP_TASK_NAME = 'Deleting IP';
    const DELETE_DETAILS = 'Delete Details';
    const EXTENDING_VOLUME_TASK_NAME = 'Extending Volume';
    const REBUILDING_VOLUME_TASK_NAME = 'Rebuilding Volume';
    const RESTORING_VOLUME_TASK_NAME = 'Restoring Volume';
    const RESTORING_VOLUME_PROCESS_TASK_NAME = 'Restoring Volume Process';
    const SCHEDULED_BACKUP_TASK_NAME = 'Scheduled Backups';
    const SETTING_VM_DETAILS_TASK_NAME = 'Setting VM Details';
    const SEND_EMAIL_TASK_NAME = 'Send Email';
    const TERMINATION_VM_TASK_NAME = 'Terminating Instance';
    const TERMINATION_ACCOUNT_TASK_NAME = 'Terminating Account';

    const TASKS = [
        self::TASK_BUILDING_VM => self::BUILDING_VM_TASK_NAME,
        self::TASK_BUILDING_VOLUME => self::BUILDING_VOLUME_TASK_NAME,
        self::TASK_CHANGING_PACKAGE => self::CHANGING_PACKAGE_TASK_NAME,
        self::TASK_CONFIRMING_RESIZE => self::CONFIRMING_RESIZE_TASK_NAME,
        self::TASK_CREATION_INSTANCE => self::CREATION_VM_TASK_NAME,
        self::TASK_CREATION_VM => self::CREATION_ACCOUNT_TASK_NAME,
        self::TASK_CREATING_INTERFACES => self::CREATING_INTERFACES_TASK_NAME,
        self::TASK_DELETE_BACKUPS => self::DELETE_BACKUPS_TASK_NAME,
        self::TASK_DELETE_BLOCK_DEVICES => self::DELETE_BLOCK_DEVICES_TASK_NAME,
        self::TASK_DELETE_PORTS => self::DELETE_PORTS_TASK_NAME,
        self::TASK_DELETE_VOLUMES => self::DELETE_VOLUMES_TASK_NAME,
        self::TASK_DELETE_FLOATING_IPS => self::DELETE_FLOATING_IPS_TASK_NAME,
        self::TASK_DELETE_IP => self::DELETE_IP_TASK_NAME,
        self::TASK_EXTENDING_VOLUME => self::EXTENDING_VOLUME_TASK_NAME,
        self::TASK_REBUILDING_VOLUME => self::REBUILDING_VOLUME_TASK_NAME,
        self::TASK_RESTORING_VOLUME => self::RESTORING_VOLUME_TASK_NAME,
        self::TASK_RESTORING_VOLUME_PROCESS => self::RESTORING_VOLUME_PROCESS_TASK_NAME,
        self::TASK_SCHEDULED_BACKUP => self::SCHEDULED_BACKUP_TASK_NAME,
        self::TASK_SETTING_VM_DETAILS => self::SETTING_VM_DETAILS_TASK_NAME,
        self::TASK_SEND_EMAIL => self::SEND_EMAIL_TASK_NAME,
        self::TASK_TERMINATION_VM => self::TERMINATION_VM_TASK_NAME,
        self::TASK_TERMINATION_ACCOUNT => self::TERMINATION_ACCOUNT_TASK_NAME,
        self::TASK_DELETE_DETAILS => self::DELETE_DETAILS,
    ];


    /**
     * @param string $className
     * @return string
     */
    public static function decorateTaskName(string $className)
    {
        foreach (self::TASKS as $task => $taskName) {
            if (stripos($className, $task) > 0) {
                return $taskName;
            }
        }

        return $className;
    }
}