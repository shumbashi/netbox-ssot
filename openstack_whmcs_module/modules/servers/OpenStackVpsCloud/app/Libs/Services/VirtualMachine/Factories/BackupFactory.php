<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Factories;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\BackupsManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces\BackupInterface;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\VolumeBackupsManager;

/**
 * Class BackupFactory
 * @package ModulesGarden\OpenStackVpsCloud\App\Libs\Factories
 */
class BackupFactory
{
    /**
     * @param ProductConfiguration $productConfig
     * @param string $vmId
     * @return BackupInterface
     */
    public static function getBackupManager(ProductConfiguration $productConfig, string $vmId)
    {
        if ($productConfig->getUseVolumes())
        {
            return new VolumeBackupsManager($vmId, $productConfig->getParams());
        }

        return new BackupsManager($vmId, $productConfig->getParams());
    }
}