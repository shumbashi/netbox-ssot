<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums;

class InstanceInfo
{
    const INSTANCE_STATUS = 'InstanceStatus';
    const INSTANCE_NAME = 'InstanceName';
    const INSTANCE_IMAGE = 'InstanceImage';
    const INSTANCE_FLAVOR_NAME = 'InstanceFlavorName';
    const INSTANCE_FLAVOR_DISK = 'InstanceFlavorDisk';
    const INSTANCE_FLAVOR_RAM = 'InstanceFlavorRam';
    const INSTANCE_FLAVOR_VCPUS = 'InstanceFlavorVcpus';
    const INSTANCE_VOLUME_SIZE = 'InstanceVolumeSize';
    const INSTANCE_REGION = 'InstanceRegion';
    const INSTANCE_RESCUE = 'InstanceRescue';
    const INSTANCE_USER_DATA = 'InstanceUserData';
    const INSTANCE_METADATA = 'InstanceMetadata';

    public static function getAll()
    {
        return [
            self::INSTANCE_STATUS,
            self::INSTANCE_NAME,
            self::INSTANCE_IMAGE,
            self::INSTANCE_FLAVOR_NAME,
            self::INSTANCE_FLAVOR_DISK,
            self::INSTANCE_FLAVOR_RAM,
            self::INSTANCE_FLAVOR_VCPUS,
            self::INSTANCE_VOLUME_SIZE,
            self::INSTANCE_REGION,
            self::INSTANCE_RESCUE,
            self::INSTANCE_USER_DATA,
            self::INSTANCE_METADATA
        ];
    }
}
