<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\FileReader;

use Exception;
use ModulesGarden\OpenStackVpsCloud\Core\FileReader\Reader\AbstractType;
use ModulesGarden\OpenStackVpsCloud\Core\FileReader\Reader\Css;
use ModulesGarden\OpenStackVpsCloud\Core\FileReader\Reader\Html;
use ModulesGarden\OpenStackVpsCloud\Core\FileReader\Reader\Ini;
use ModulesGarden\OpenStackVpsCloud\Core\FileReader\Reader\Js;
use ModulesGarden\OpenStackVpsCloud\Core\FileReader\Reader\Json;
use ModulesGarden\OpenStackVpsCloud\Core\FileReader\Reader\Php;
use ModulesGarden\OpenStackVpsCloud\Core\FileReader\Reader\Sql;
use ModulesGarden\OpenStackVpsCloud\Core\FileReader\Reader\Xml;
use ModulesGarden\OpenStackVpsCloud\Core\FileReader\Reader\Yml;

/**
 * Class Reader
 *
 * Factory class for reading and processing different file types.
 * Automatically detects file type by extension and instantiates the appropriate reader.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\FileReader
 */
class Reader
{
    /**
     * Read and process a file based on its extension.
     *
     * @param string $file File path to read
     * @param array $renderData Optional data for template rendering
     * @return AbstractType Appropriate reader instance for the file type
     * @throws Exception When file type is not supported
     */
    public static function read($file, array $renderData = [])
    {
        $path = explode(DIRECTORY_SEPARATOR, $file);
        $file = end($path);
        array_pop($path);
        $path     = implode(DIRECTORY_SEPARATOR, $path);
        $instance = null;
        $type     = self::getType($file);

        switch ($type)
        {
            case 'xml':
                $instance = new Xml($file, $path, $renderData);
                break;
            case 'ini':
                $instance = new Ini($file, $path, $renderData);
                break;
            case 'yml':
                $instance = new Yml($file, $path, $renderData);
                break;
            case 'json':
                $instance = new Json($file, $path, $renderData);
                break;
            case 'php':
                $instance = new Php($file, $path, $renderData);
                break;
            case 'sql':
                $instance = new Sql($file, $path, $renderData);
                break;
            case 'js':
                $instance = new Js($file, $path, $renderData);
                break;
            case 'css':
                $instance = new Css($file, $path, $renderData);
                break;
            case 'html':
                $instance = new Html($file, $path, $renderData);
                break;
            default:
                throw new Exception('Can\'t read file: ' . $file);
        }

        return $instance;
    }

    /**
     * Extract the file type/extension from a filename.
     *
     * @param string $file Filename to analyze
     * @return string Lowercase file extension
     */
    private static function getType($file)
    {
        $type  = null;
        $array = explode('.', $file);
        if (is_array($array))
        {
            $type = end($array);
        }

        return strtolower($type);
    }
}
