<?php

namespace ModulesGarden\OpenStackVpsCloud\Core;

use function ModulesGarden\OpenStackVpsCloud\Core\Helper\isAdmin;

/**
 * Class ModuleConstants
 *
 * Central configuration class that provides constants and paths for the module framework.
 * Manages module paths, namespaces, database prefixes, and other core configuration values.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core
 */
class ModuleConstants
{
    /**
     * Default controller action name.
     */
    public const DEFAULT_CONTROLLER_ACTION = 'index';

    /**
     * Parameter name for controller action in HTTP requests.
     */
    public const CONTROLLER_ACTION_PARAMETER = 'mg-action';

    /**
     * Parameter name for controller page in HTTP requests.
     */
    public const CONTROLLER_PAGE_PARAMETER = 'mg-page';

    /**
     * Admin level constant.
     */
    public const LEVEL_ADMIN             = 'admin';
    /**
     * Client level constant.
     */
    public const LEVEL_CLIENT            = 'client';
    /**
     * Auto-detect level constant.
     */
    public const LEVEL_AUTO              = '';
    /**
     * Addon module type constant.
     */
    public const MODULE_TYPE_ADDON       = 'addon';
    /**
     * Provisioning module type constant.
     */
    public const MODULE_TYPE_PROVISIONIG = 'provisioning';

    /**
     * Core configuration directory path.
     *
     * @var string|null
     */
    protected static $mgCoreConfig = null;
    /**
     * Development configuration directory path.
     *
     * @var string|null
     */
    protected static $mgDevConfig = null;
    /**
     * Module namespace prefix.
     *
     * @var string
     */
    protected static $mgModuleNamespace = "ModulesGarden\OpenStackVpsCloud";
    /**
     * Module root directory path.
     *
     * @var string|null
     */
    protected static $mgModuleRootDir = null;
    /**
     * Template directory path.
     *
     * @var string|null
     */
    protected static $mgTemplateDir = null;
    /**
     * Database table prefix.
     *
     * @var string
     */
    protected static $prefixDataBase = '';
    /**
     * Resources directory path.
     *
     * @var string|null
     */
    protected static $resourcesDir = null;

    /**
     * Get the core configuration directory path.
     *
     * @return string|null The core configuration directory path
     */
    public static function getCoreConfigDir()
    {
        return self::$mgCoreConfig;
    }

    /**
     * Get the development configuration directory path.
     *
     * @return string|null The development configuration directory path
     */
    public static function getDevConfigDir()
    {
        return self::$mgDevConfig;
    }

    /**
     * Get the full namespace by combining root namespace with additional directories.
     *
     * @param string ...$dirs Additional directory names to append to namespace
     * @return string The full namespace
     */
    public static function getFullNamespace()
    {
        $fullNamespace = self::getRootNamespace();
        foreach (func_get_args() as $dir)
        {
            $fullNamespace .= ('\\' . $dir);
        }

        return $fullNamespace;
    }

    /**
     * Get the root namespace of the module.
     *
     * @return string The root namespace
     */
    public static function getRootNamespace()
    {
        return self::$mgModuleNamespace;
    }

    /**
     * Get the full path within WHMCS root directory.
     *
     * @param string ...$dirs Directory names to append to WHMCS root path
     * @return string The full WHMCS path
     */
    public static function getFullPathWhmcs()
    {
        $fullPath = ROOTDIR;
        foreach (func_get_args() as $dir)
        {
            $fullPath .= (DIRECTORY_SEPARATOR . $dir);
        }

        return $fullPath;
    }

    /**
     * Get the database table prefix with trailing underscore.
     *
     * @return string The database prefix with underscore
     */
    public static function getPrefixDataBase()
    {
        return self::$prefixDataBase . '_';
    }

    /**
     * Get the assets directory path.
     *
     * @param string ...$dir Additional directory names to append
     * @return string The assets directory path
     */
    public static function getAssetsDir(...$dir)
    {
        return self::getResourcesDir() . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $dir);
    }

    /**
     * Get the resources directory path.
     *
     * @return string|null The resources directory path
     */
    public static function getResourcesDir()
    {
        return self::$resourcesDir;
    }

    /**
     * Get the template directory path.
     *
     * @return string|null The template directory path
     */
    public static function getTemplateDir()
    {
        return self::$mgTemplateDir;
    }

    /**
     * Initialize the module constants with default values.
     *
     * @return void
     */
    public static function initialize()
    {
        self::$mgModuleRootDir = dirname(__DIR__);
        self::$mgDevConfig     = self::getFullPath('app', 'Config');
        self::$mgCoreConfig    = self::getFullPath('core', 'Config');
        self::$mgTemplateDir   = self::getFullPath('resources', 'views');
        self::$resourcesDir    = self::getFullPath('resources');
        self::$prefixDataBase  = self::loadDataBasePrefix();
    }

    /**
     * Get the full path within the module directory.
     *
     * @param string ...$elements Path elements to join
     * @return string The full module path
     */
    public static function getFullPath(...$elements)
    {
        $fullPath = self::getModuleRootDir();
        foreach ($elements as $dir)
        {
            $fullPath .= (DIRECTORY_SEPARATOR . $dir);
        }

        return $fullPath;
    }

    /**
     * Get the module root directory path.
     *
     * @return string|null The module root directory path
     */
    public static function getModuleRootDir()
    {
        return self::$mgModuleRootDir;
    }

    /**
     * Load the database prefix for the module.
     *
     * @return string The database prefix
     */
    public static function loadDataBasePrefix(): string
    {
        return self::getModuleName();
    }

    /**
     * Get the module name from the directory structure.
     *
     * @return string The module name
     */
    public static function getModuleName(): string
    {
        return basename(self::$mgModuleRootDir);
    }

    /**
     * Get the module type from the directory structure.
     *
     * @return string The module type
     */
    public static function getModuleType(): string
    {
        return basename(dirname(self::$mgModuleRootDir));
    }


    public static function getLevel(): string
    {
        return isAdmin() ? self::LEVEL_ADMIN : self::LEVEL_CLIENT;
    }
}
