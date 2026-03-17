<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\CommandLine\LoadCommands;

/**
 * Interface LoadCommandsControllersInterface
 *
 * Contract for classes that load and provide command controllers.
 * Implementations of this interface locate command classes from various sources
 * and return them to the application for registration.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\CommandLine\LoadCommands
 */
interface LoadCommandsControllersInterface
{
    /**
     * Get an array of command class names that should be registered.
     *
     * @param string|null $dir Optional directory to search for commands
     * @return array Array of fully qualified command class names
     */
    public function getCommands(string $dir = null): array;
}
