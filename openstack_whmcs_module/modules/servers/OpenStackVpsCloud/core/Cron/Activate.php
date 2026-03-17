<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Cron;

use ModulesGarden\OpenStackVpsCloud\Core\CommandLine\AbstractCommand;
use ModulesGarden\OpenStackVpsCloud\Core\Module\Addon;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class Activate
 *
 * CLI command for module activation.
 * Provides functionality to activate modules through the command line interface.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Cron
 */
class Activate extends AbstractCommand
{
    /**
     * Command name for the activate command.
     *
     * @var string
     */
    protected $name = 'module:activate';

    /**
     * Activate constructor.
     * Initializes the command with name and aliases.
     */
    public function __construct()
    {
        parent::__construct($this->name);

        $this->setAliases(['activate']);
    }

    /**
     * Process the activate command.
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
            $io->info('Run module activate');

            Addon::activate();

            $io->success('Module activated successfully');
        }
        catch (\Exception $ex)
        {
            $io->error($ex->getMessage());
        }
    }

}