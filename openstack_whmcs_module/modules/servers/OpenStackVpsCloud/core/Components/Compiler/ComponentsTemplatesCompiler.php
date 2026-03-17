<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Components\Compiler;

use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use Symfony\Component\Process\Process;

/**
 * ComponentsTemplatesCompiler
 *
 * Provides functionality to compile components templates and save compiled output file.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Components\Compiler
 */
class ComponentsTemplatesCompiler
{
    /**
     * Run compile process.
     *
     * @return string The path to output file with compiled components templates.
     */
    public static function compile():string
    {
        $compiler = new self();
        $compiler->loadLibraries();

        return $compiler->runCompilingProcess();
    }

    /**
     * Run load related libraries command.
     *
     * @return void
     */
    protected function loadLibraries():void
    {
        self::processCommand("npm i vue-template-compiler");
    }

    /**
     * Run compile process npm command.
     *
     * @return string The path to output file with compiled components templates.
     */
    protected function runCompilingProcess():string
    {
        $compilerToolDir = ModuleConstants::getAssetsDir('utilities', 'tools', 'components-templates-compiler.js');
        $compiledFileDir = CompilerOutputFileInfo::getOutputFilePath();

        if (!file_exists($compilerToolDir))
        {
            throw new \Exception(sprintf("Compiler tool not found at %s", $compilerToolDir));
        }

        self::processCommand('node ' . $compilerToolDir . ' --output=' . $compiledFileDir);

        return $compiledFileDir;
    }

    /**
     * Run and handle process.
     *
     * @return void
     */
    protected function processCommand(string $command):void
    {
        $commandElements = explode(' ', $command);
        $process = new Process($commandElements, __DIR__);
        $process->run();
    }
}