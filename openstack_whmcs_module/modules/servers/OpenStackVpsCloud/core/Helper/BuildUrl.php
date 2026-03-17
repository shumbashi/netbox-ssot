<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Helper;

use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Routing\Url;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;

/**
 * Class BuildUrl
 *
 * Helper class for constructing and managing URLs in the application.
 * Provides methods to generate various types of URLs including current, base, full, and asset URLs.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Helper
 */
class BuildUrl
{
    /**
     * Ports that don't need to be explicitly included in URLs.
     *
     * @var array
     */
    protected const RESERVED_PORTS = [80, 443];

    /**
     * Get the current URL including query string.
     *
     * @return string The current URL with query parameters
     */
    public static function currentUrl()
    {
        $qs = html_entity_decode($_SERVER['QUERY_STRING']);

        return self::fullUrl() . ($qs ? '?' . $qs : '');
    }

    /**
     * Get the base URL for the application.
     *
     * @return string The base URL with trailing slash
     */
    public static function getBaseUrl(): string
    {
        $scheme = self::getScheme();
        $host   = self::getHost();
        $suffix = self::getUrlSuffix();
        $port   = self::getPortPrefixed();

        return "{$scheme}://{$host}{$port}{$suffix}/";
    }

    /**
     * Get the full URL to the current script.
     *
     * @return string The full URL to the current script
     */
    public static function fullUrl(): string
    {
        $scheme = self::getScheme();
        $host   = self::getHost();
        $port   = self::getPortPrefixed();

        return "{$scheme}://{$host}{$port}{$_SERVER['PHP_SELF']}";
    }

    /**
     * Get the root URL of the application.
     *
     * @return string The root URL with trailing slash
     */
    public static function rootUrl(): string
    {
        $scheme     = self::getScheme();
        $host       = self::getHost();
        $hostPath   = self::getHostPath();
        $port       = self::getPortPrefixed();

        return "{$scheme}://{$host}{$port}{$hostPath}/";
    }

    /**
     * Get the URL scheme (http or https).
     *
     * @return string The URL scheme (http or https)
     */
    public static function getScheme(): string
    {
        return self::getVisitorScheme() ?? self::getBaseScheme();
    }

    /**
     * Get the host name from the system URL or HTTP host.
     *
     * @return string The host name
     */
    public static function getHost(): string
    {
        $host = $GLOBALS['CONFIG']['SystemURL'] ?: $_SERVER['HTTP_HOST'];
        $url  = \parse_url($host);

        return $url['host'] ?? '';
    }

    /**
     * Get the path component from the host URL.
     *
     * @return string The path component
     */
    public static function getHostPath(): string
    {
        $host = $GLOBALS['CONFIG']['SystemURL'] ?: $_SERVER['HTTP_HOST'];
        $url  = \parse_url($host);

        return $url['path'] ?? '';
    }

    /**
     * Get the base scheme from server settings.
     *
     * @return string The base scheme (http or https)
     */
    public static function getBaseScheme()
    {
        return $_SERVER["HTTP_X_FORWARDED_PROTO"] ??
               ((!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') ? 'http' : 'https');
    }


    /**
     * Get the visitor scheme from Cloudflare visitor headers.
     *
     * @return string|null The visitor scheme or null if not available
     */
    public static function getVisitorScheme()
    {
        if (empty($_SERVER['HTTP_CF_VISITOR']))
        {
            return null;
        }

        $visitorParams = (array)json_decode(html_entity_decode($_SERVER['HTTP_CF_VISITOR']));
        return $visitorParams['scheme'] ?? null;
    }

    /**
     * Get the URL suffix (path without filename).
     *
     * @return string The URL suffix
     */
    public static function getUrlSuffix(): string
    {
        $suffix = $_SERVER['PHP_SELF'] ?: '';
        $suffix = explode('/', $suffix);
        array_pop($suffix);
        return implode('/', $suffix);
    }

    /**
     * Get the port from the HTTP host or system URL.
     *
     * @return string|null The port or null if not specified
     */
    public static function getPort(): ?string
    {
        $host = $_SERVER['HTTP_HOST'] ?: $GLOBALS['CONFIG']['SystemURL'];

        $url = \parse_url($host);

        return $url['port'];
    }

    /**
     * Get the URL to resources in the module.
     *
     * @param string ...$path Path segments to append
     * @return string The resources URL
     */
    public static function getResourcesURL(...$path): string
    {
        global $CONFIG;

        return $CONFIG['SystemURL'] . '/modules/' . ModuleConstants::getModuleType() . '/' . ModuleConstants::getModuleName() . '/resources/' . implode('/', $path);
    }

    /**
     * Get the URL to assets in the module.
     *
     * @param string ...$path Path segments to append
     * @return string The assets URL
     */
    public static function getAssetsURL(...$path): string
    {
        return self::getResourcesURL('assets') . '/' . implode('/', $path);
    }

    /**
     * Get the URL to packages in the module.
     *
     * @param string ...$path Path segments to append
     * @return string The packages URL
     */
    public static function getPackagesURL(...$path): string
    {
        global $CONFIG;

        return $CONFIG['SystemURL'] . '/modules/' . ModuleConstants::getModuleType() . '/' . ModuleConstants::getModuleName() . "/packages/" . implode('/', $path);
    }

    /**
     * Get the URL to components in the module.
     *
     * @param string ...$path Path segments to append
     * @return string The components URL
     */
    public static function getComponentsURL(...$path): string
    {
        global $CONFIG;

        return $CONFIG['SystemURL'] . '/modules/' . ModuleConstants::getModuleType() . '/' . ModuleConstants::getModuleName() . "/components/" . implode('/', $path);
    }

    /**
     * Create a new URL with the specified protocol, host, and parameters.
     *
     * @param string $protocol The URL protocol
     * @param string $host The URL host
     * @param array $params Query parameters
     * @return string The constructed URL
     */
    public static function getNewUrl($protocol = 'http', $host = 'modulesgarden.com', $params = []): string
    {
        $url = "{$protocol}://{$host}";
        if ($params)
        {
            $url .= '?' . http_build_query($params);
        }

        return $url;
    }

    /**
     * Get the URL to which requests should be directed.
     *
     * @return string The module request URL
     */
    public static function getModuleRequestUrl():string
    {
        return isAdmin() ? self::getAdminAreaModuleRequestUrl() : self::getClientAreaModuleRequestUrl();
    }

    /**
     * Get the URL to which requests should be directed in Admin Area.
     *
     * @return string The module request URL
     */
    protected static function getAdminAreaModuleRequestUrl():string
    {
        return in_array(basename($_SERVER["SCRIPT_NAME"]), ['clientsservices.php', 'configproducts.php']) ?
            Url::adminarea(basename($_SERVER["SCRIPT_NAME"]), Request::query()->all()) :
            Url::adminarea('addonmodules.php', array_merge(Request::query()->all(), ['module' => ModuleConstants::getModuleName()]));
    }

    /**
     * Get the URL to which requests should be directed in Client Area.
     *
     * @return string The module request URL
     */
    protected static function getClientAreaModuleRequestUrl():string
    {
        return basename($_SERVER["SCRIPT_NAME"]) == 'clientarea.php' ?
            Url::clientarea('clientarea.php', Request::query()->all()) :
            Url::clientarea('index.php', array_merge(Request::query()->all(), ['m' => ModuleConstants::getModuleName()]));
    }

    /**
     * Get a module URL based on controller, action, and parameters.
     *
     * @param string|null $controller The controller name
     * @param string|null $action The action name
     * @param array $params Query parameters
     * @param bool $isFullUrl Whether to return a full URL or a relative URL
     * @return string The constructed URL
     */
    public static function getUrl($controller = null, $action = null, array $params = [], $isFullUrl = true)
    {
        if (isAdmin())
        {
            $url = 'addonmodules.php?module=' . ModuleConstants::getModuleName();
        }
        else
        {
            $url = 'index.php?m=' . ModuleConstants::getModuleName();
        }

        if ($controller)
        {
            $url .= '&mg-page=' . $controller;

            if ($action)
            {
                $url .= '&mg-action=' . $action;
            }

            if ($params)
            {
                $url .= '&' . http_build_query($params);
            }
        }

        if ($isFullUrl)
        {
            $baseUrl = self::getBaseUrl();
            $url     = $baseUrl . $url;
        }

        return $url;
    }

    /**
     * Get a valid port, filtering out standard ports.
     *
     * @return string|null The port if non-standard, null otherwise
     */
    public static function getValidPort(): ?string
    {
        $port = self::getPort();

        return in_array($port, self::RESERVED_PORTS) ? null : $port;
    }

    /**
     * Get the port with prefix if needed.
     *
     * @return string The port with colon prefix or empty string
     */
    protected static function getPortPrefixed(): string
    {
        $port = self::getValidPort();

        return $port ? ":{$port}" : "";
    }
}
