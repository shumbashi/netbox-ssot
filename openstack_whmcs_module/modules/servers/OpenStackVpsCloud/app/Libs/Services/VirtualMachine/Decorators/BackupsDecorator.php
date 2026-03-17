<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Decorators;

/**
 * Class BackupsDecorator
 * @package ModulesGarden\OpenStackVpsCloud\App\Libs\Decorators\Vps
 */
class BackupsDecorator
{
    const NAME_PREFIX               = 'Backup_';
    const VOLUME_BACKUP_NAME_PREFIX = 'Volume_Backup_';

    /**
     * @return string
     */
    public static function decorateName()
    {
        return self::NAME_PREFIX . date('Y-m-d H:i:s');
    }

    /**
     * @return string
     */
    public static function decorateVolumeBackupName()
    {
        return self::VOLUME_BACKUP_NAME_PREFIX . date('Y-m-d H:i:s');
    }
}