<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Cron;

use ModulesGarden\OpenStackVpsCloud\Core\CommandLine\AbstractCommand;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Compiler\ComponentsTemplatesCompiler;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class BuildAssets
 *
 * CLI command for build assets - compile components template.
 * Provides functionality to compile components template through the command line interface.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Cron
 */
class BuildAssets extends AbstractCommand
{
    /**
     * Command name for the activate command.
     *
     * @var string
     */
    protected $name = 'build:assets';

    /**
     * Activate constructor.
     * Initializes the command with name and aliases.
     */
    public function __construct()
    {
        parent::__construct($this->name);

        $this->setAliases(['buildAssets']);
    }

    /**
     * Process the buildAsset command.
     *
     * @param InputInterface $input CLI input interface
     * @param OutputInterface $output CLI output interface
     * @param SymfonyStyle $io Symfony style helper
     * @return void
     */
    protected function process(InputInterface $input, OutputInterface $output, SymfonyStyle $io)
    {
        try
        {
            $io->info('Run assets build process...');

            $compiledOutputFile = ComponentsTemplatesCompiler::compile();

            $io->success("Build assets command finished successfully!");

            $io->info(sprintf("Output file: %s", $compiledOutputFile));
        }
        catch (\Exception $ex)
        {
            $io->error($ex->getMessage());
        }
    }

}