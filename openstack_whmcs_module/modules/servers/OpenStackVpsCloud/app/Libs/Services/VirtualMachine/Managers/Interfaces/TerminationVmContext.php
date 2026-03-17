<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces;

/**
 * Interface TerminationVmContext
 * @package ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\Interfaces
 */
interface TerminationVmContext
{
    public function runDeleteVm(string $specificVmIdToDelete = null);
}