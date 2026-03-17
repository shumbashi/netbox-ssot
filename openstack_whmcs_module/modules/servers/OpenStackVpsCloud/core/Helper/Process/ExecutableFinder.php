<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Helper\Process;

use \Symfony\Component\Process\ExecutableFinder as BaseExecutableFinder;

/**
 * @method setSuffixes(array $suffixes)
 * @method addSuffix(string $suffix)
 * @method string|null find(string $name, string $default = null, array $extraDirs = [])
 */
class ExecutableFinder extends BaseExecutableFinder
{
    public function findWithChecker(callable $checker, string $name, string $default = null, array $extraDirs = []):?string
    {
        if (ini_get('open_basedir'))
        {
            $searchPath = array_merge(explode(\PATH_SEPARATOR, ini_get('open_basedir')), $extraDirs);
            $dirs = [];
            foreach ($searchPath as $path)
            {
                if (@is_dir($path))
                {
                    $dirs[] = $path;
                    continue;
                }

                if (basename($path) == $name && @is_executable($path) && $checker($path))
                {
                    return $path;
                }
            }
        }
        else
        {
            $dirs = array_merge(
                explode(\PATH_SEPARATOR, getenv('PATH') ?: getenv('Path')),
                $extraDirs
            );
        }

        $suffixes = [''];

        if ('\\' === \DIRECTORY_SEPARATOR)
        {
            $pathExt = getenv('PATHEXT');
            $suffixes = array_merge($pathExt ? explode(\PATH_SEPARATOR, $pathExt) : $this->suffixes, $suffixes);
        }

        foreach ($suffixes as $suffix)
        {
            foreach ($dirs as $dir)
            {
                if (@is_file($file = $dir.\DIRECTORY_SEPARATOR.$name.$suffix) && ('\\' === \DIRECTORY_SEPARATOR || @is_executable($file)) && $checker($file))
                {
                    return $file;
                }
            }
        }

        return $default;
    }
}