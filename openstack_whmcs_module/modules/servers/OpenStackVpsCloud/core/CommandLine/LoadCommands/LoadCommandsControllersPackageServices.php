<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\CommandLine\LoadCommands;

use ModulesGarden\OpenStackVpsCloud\Core\DependencyInjection\PackageServices;
use function ModulesGarden\OpenStackVpsCloud\Core\make;

/**
 * Class LoadCommandsControllersPackageServices
 *
 * Loads command controllers from package services using dependency injection.
 * Implements LoadCommandsControllersInterface to provide command classes from packages.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\CommandLine\LoadCommands
 */
class LoadCommandsControllersPackageServices implements LoadCommandsControllersInterface
{
    /**
     * Get an array of command class names from package services.
     *
     * @param string|null $dir Not used in this implementation
     * @return array Array of fully qualified command class names
     */
    public function getCommands(string $dir = null): array
    {
        $commands = make(PackageServices::class)->getCommands();

        return $commands;
    }
}
