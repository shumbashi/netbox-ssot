<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Cron;

use ModulesGarden\OpenStackVpsCloud\Core\CommandLine\AbstractCommand;
use ModulesGarden\OpenStackVpsCloud\Core\Database\DatabaseManager;
use ModulesGarden\OpenStackVpsCloud\Core\Module\Addon;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class Deactivate
 *
 * CLI command for module deactivation with optional database table removal.
 * Provides functionality to deactivate modules and optionally clean up database tables.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Cron
 */
class Deactivate extends AbstractCommand
{
    /**
     * Option name for removing database tables during deactivation.
     */
    protected const REMOVE_DATABASE_TABLES_OPTION = "remove-database-tables";

    /**
     * Command name for the deactivate command.
     *
     * @var string
     */
    protected $name = 'module:deactivate';

    /**
     * Deactivate constructor.
     * Initializes the command with name and aliases.
     */
    public function __construct()
    {
        parent::__construct($this->name);

        $this->setAliases(['deactivate']);
    }

    /**
     * Setup command options and arguments.
     *
     * @return void
     */
    protected function setup()
    {
        $this->addOption(self::REMOVE_DATABASE_TABLES_OPTION, null, InputOption::VALUE_OPTIONAL, "Remove Data Tables");
    }

    /**
     * Process the deactivate command.
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
            $io->info('Run module deactivate');

            Addon::deactivate();

            if ($input->hasParameterOption("--" . self::REMOVE_DATABASE_TABLES_OPTION))
            {
                $this->askAndRemoveDataBaseTables($input, $output, $io);
            }

            $io->success('Module deactivated successfully');
        }
        catch (\Exception $ex)
        {
            $io->error($ex->getMessage());
        }
    }

    /**
     * Ask user confirmation and remove database tables if confirmed.
     *
     * @param InputInterface $input CLI input interface
     * @param OutputInterface $output CLI output interface
     * @param SymfonyStyle $io Symfony style helper
     * @return void
     */
    protected function askAndRemoveDataBaseTables(InputInterface $input, OutputInterface $output, SymfonyStyle $io)
    {
        if ($io->confirm("Are you absolutely sure you want to drop all database tables?", false))
        {
            (new DatabaseManager())->dropAllModuleTables();

            $io->success('Database tables dropped successfully');
        }
    }

}