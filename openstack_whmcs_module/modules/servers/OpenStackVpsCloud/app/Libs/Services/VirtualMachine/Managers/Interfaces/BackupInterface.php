<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces;

interface BackupInterface
{
    public function createBackup(string $backupName);

    public function restoreBackup(string $id, string $adminPassword);

    public function deleteBackups(array $backupsID = []);

    public function getBackups();

    public function getBackupName();

    public function deleteAllBackups();
}