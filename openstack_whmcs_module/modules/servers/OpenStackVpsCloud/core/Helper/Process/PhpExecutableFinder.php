<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Helper\Process;

use Symfony\Component\Process\PhpExecutableFinder as BasePhpExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * @method string|false find(bool $includeArgs = true)
 * More information here: https://symfony.com/doc/current/components/process.html#finding-the-executable-php-binary
 */
class PhpExecutableFinder extends BasePhpExecutableFinder
{
    const CLI_SAPI = 'cli';

    public function __construct()
    {
        $this->executableFinder = new ExecutableFinder();
    }

    public static function isCliExecutable(string $phpPath): bool
    {
        $process = new Process([$phpPath, '-r', 'echo PHP_SAPI;']);

        $process->run();

        return $process->isSuccessful() && $process->getOutput() === self::CLI_SAPI;
    }

    public function findCliExecutable(bool $includeArgs = true):string|false
    {
        return $this->findWithChecker([self::class, 'isCliExecutable'], $includeArgs);
    }

    public function findWithChecker(callable $checker, bool $includeArgs = true):string|false
    {
        if ($phpFile = getenv('PHP_BINARY'))
        {
            if (!is_executable($phpFile))
            {
                $command = '\\' === \DIRECTORY_SEPARATOR ? 'where' : 'command -v';
                $phpFile = strtok(exec($command.' '.escapeshellarg($phpFile)), \PHP_EOL);
            }

            if (is_executable($phpFile) && $checker($phpFile))
            {
                return $phpFile;
            }
        }

        $args = $this->findArguments();
        $args = $includeArgs && $args ? ' '.implode(' ', $args) : '';

        if (\PHP_BINARY && \in_array(\PHP_SAPI, ['cgi-fcgi', 'cli', 'cli-server', 'phpdbg'], true) && $checker(\PHP_BINARY))
        {
            return \PHP_BINARY.$args;
        }

        if (@is_executable($phpFile = getenv('PHP_PATH')) && $checker($phpFile))
        {
            return $phpFile;
        }

        if (@is_executable($phpFile = getenv('PHP_PEAR_PHP_BIN')) && $checker($phpFile))
        {
            return $phpFile;
        }

        if (@is_executable($phpFile = \PHP_BINDIR.('\\' === \DIRECTORY_SEPARATOR ? '\\php.exe' : '/php')) && $checker($phpFile))
        {
            return $phpFile;
        }

        $dirs = [\PHP_BINDIR];

        if ('\\' === \DIRECTORY_SEPARATOR)
        {
            $dirs[] = 'C:\xampp\php\\';
        }

        return $this->executableFinder->findWithChecker($checker, 'php',false, $dirs);
    }
}