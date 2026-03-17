<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\FileReader;

/**
 * Class Directory
 *
 * Utility class for directory operations and file listing.
 * Extends PathValidator to inherit path validation capabilities.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\FileReader
 */
class Directory extends PathValidator
{
    /**
     * Get a list of files from a directory with optional filtering by extension.
     *
     * @param string $path Directory path to scan
     * @param string|null $extension Filter files by this extension (e.g., '.php')
     * @param bool $trimExtensions Whether to remove extensions from filenames in the result
     * @return array List of filenames that match the criteria
     */
    public function getFilesList($path, $extension = null, $trimExtensions = false)
    {
        if (!$this->pathExists($path) || !$this->isPathReadable($path))
        {
            return [];
        }

        $list  = [];
        $files = scandir($path, 1);
        if (!$files)
        {
            return [];
        }

        foreach ($files as $key => $value)
        {
            //remove dots and a files with unwanted extensions
            if ($value === '.' || $value === '..' ||
                (is_string($extension) && $extension !== '' && !(stripos($value, $extension) > 0)))
            {
                unset($files[$key]);
                continue;
            }

            $list[] = $trimExtensions ? str_replace($extension, '', $value) : $value;
        }

        return $list;
    }
}
