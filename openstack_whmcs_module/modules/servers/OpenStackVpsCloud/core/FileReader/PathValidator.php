<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\FileReader;

/**
 * Class PathValidator
 *
 * Utility class for validating and managing file system paths.
 * Provides methods to check if paths exist, are readable or writable,
 * and to create directories when needed.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\FileReader
 */
class PathValidator
{
    /**
     * Validate a file system path based on specified requirements.
     *
     * @param string $path Path to validate (file or directory)
     * @param bool $isReadable Whether the path should be readable
     * @param bool $isWritable Whether the path should be writable
     * @param bool $create Whether to create the directory if it doesn't exist
     * @return bool True if path meets all requirements, false otherwise
     */
    public function validatePath($path = '', $isReadable = true, $isWritable = false, $create = true)
    {
        //try to create a dir if does not exist
        if ($create)
        {
            $this->createDirIfNotExists($path);
        }

        //if path does not exists
        if (!$this->pathExists($path))
        {
            return false;
        }

        //if should be readable and it is not
        if ($isReadable && !$this->isPathReadable($path))
        {
            return false;
        }

        //if should be writable and it is not
        if ($isWritable && !$this->isPathWritable($path))
        {
            return false;
        }

        return true;
    }

    /**
     * Create a directory if it does not exist.
     *
     * @param string $path Path where directory should be created
     * @return void
     */
    public function createDirIfNotExists($path)
    {
        if (!$this->pathExists($path))
        {
            mkdir($path);
        }
    }

    /**
     * Check if a path exists in the file system.
     *
     * @param string $path Path to check (file or directory)
     * @return bool True if the path exists, false otherwise
     */
    public function pathExists($path)
    {
        return file_exists($path);
    }

    /**
     * Check if a path is readable.
     *
     * @param string $path Path to check (file or directory)
     * @return bool True if the path is readable, false otherwise
     */
    public function isPathReadable($path)
    {
        return is_readable($path);
    }

    /**
     * Check if a path is writable.
     *
     * @param string $path Path to check (file or directory)
     * @return bool True if the path is writable, false otherwise
     */
    public function isPathWritable($path)
    {
        return is_writable($path);
    }
}
