<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\CommandLine;

/**
 * Class CommandHelper
 *
 * Provides helper methods for command-line interface detection.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\CommandLine
 */
class CommandHelper
{
    /**
     * Determine if the script is called via CLI.
     *
     * @return bool True if called via CLI, false otherwise
     */
    public static function calledViaCli(): bool
    {
        return in_array(php_sapi_name(), ["cli", "cli-server"]) ||
               (!isset($_SERVER["SERVER_NAME"]) && !isset($_SERVER["HTTP_HOST"]));
    }
}