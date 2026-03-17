<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\CommandLine\LoadCommands;

use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;

/**
 * Class LoadCommandsControllers
 *
 * Loads command controllers from the module's app directory.
 * Implements LoadCommandsControllersInterface to discover and return command classes.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\CommandLine\LoadCommands
 */
class LoadCommandsControllers implements LoadCommandsControllersInterface
{
    /**
     * Get an array of command class names from the module's app directory.
     *
     * @param string|null $dir The subdirectory within app to search for commands
     * @return array Array of fully qualified command class names
     */
    public function getCommands(string $dir = null): array
    {
        $files    = $this->getFiles($dir);
        $commands = [];

        $dir = str_replace('/', DIRECTORY_SEPARATOR, $dir);
        foreach ($files as $file)
        {
            $commands[] = ModuleConstants::getRootNamespace() . '\App\\' . $dir . '\\' . $file;
        }

        return $commands;
    }

    /**
     * Get PHP files from the specified directory within the app folder.
     *
     * @param string $dir The subdirectory within app to scan
     * @return array Array of class names without namespace or extension
     */
    protected function getFiles(string $dir): array
    {
        $files    = glob(ModuleConstants::getFullPath('app', $dir) . DIRECTORY_SEPARATOR . '*.php');
        $commands = [];

        foreach ($files as $file)
        {
            $file = substr($file, strrpos($file, DIRECTORY_SEPARATOR) + 1);
            $file = substr($file, 0, strrpos($file, '.'));

            $commands[] = $file;
        }

        return $commands;
    }
}
