<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Components\Compiler;

use ModulesGarden\OpenStackVpsCloud\Core\Helper\BuildUrl;
use ModulesGarden\OpenStackVpsCloud\Core\Helper\ModuleVersionComparator;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;

/**
 * CompilerOutputFileInfo
 *
 * Keep and find Compiled output file info etc. name, path URL.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Components\Compiler
 */
class CompilerOutputFileInfo
{
    protected const OUTPUT_FILE_NAME = 'compiled-components-templates';
    protected const DEFAULT_VERSION = '1.0.0';

    /**
     *
     * @return string URL to compiled output file
     */
    public static function getOutputFileUrl():string
    {
        return BuildUrl::getResourcesURL('utilities', 'source', self::OUTPUT_FILE_NAME . '-' . self::findModuleVersion() . '.js');
    }

    /**
     *
     * @return string path to compiled output file
     */
    public static function getOutputFilePath():string
    {
        return ModuleConstants::getAssetsDir('utilities', 'source', self::OUTPUT_FILE_NAME . '-' . self::findModuleVersion() . '.js');
    }

    /**
     *
     * @return string founded module version
     */
    protected static function findModuleVersion():string
    {
        return ModuleVersionComparator::getMaxVersion(self::getVersionFromModuleVersionFile(), self::getVersionFromConfig());
    }

    /**
     *
     * @return string founded module version from moduleVersion.php file
     */
    protected static function getVersionFromModuleVersionFile():string
    {
        $moduleVersionFile = ModuleConstants::getFullPath('moduleVersion.php');

        if (!file_exists($moduleVersionFile))
        {
            return self::DEFAULT_VERSION;
        }

        $moduleVersion = null;

        (function () use ($moduleVersionFile, &$moduleVersion) {
            include $moduleVersionFile;
        })();

        return trim($moduleVersion);
    }

    /**
     *
     * @return string founded module version from configuration
     */
    protected static function getVersionFromConfig():string
    {
        return Config::get("configuration.version", self::DEFAULT_VERSION);
    }
}