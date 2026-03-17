<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Cron;

use ModulesGarden\OpenStackVpsCloud\Core\CommandLine\AbstractCommand;
use ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\Update\PatchManager;
use ModulesGarden\OpenStackVpsCloud\Core\Module\Addon;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\TableCell;

/**
 * Class Upgrade
 *
 * Provides CLI commands for listing and running module upgrades.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Cron
 */
class Upgrade extends AbstractCommand
{
    /**
     * Action name for listing upgrades.
     */
    public const ACTION_LIST    = 'list';
    /**
     * Action name for running an upgrade.
     */
    public const ACTION_RUN     = 'run';

    /**
     * Command name.
     *
     * @var string
     */
    protected $name = 'module:upgrade';
    /**
     * Available CLI actions.
     *
     * @var array
     */
    protected array $availableActions = [];

    /**
     * Upgrade constructor.
     * Initializes the command and available actions.
     */
    public function __construct()
    {
        parent::__construct($this->name);

        $this->setAliases(['upgrade']);
        $this->initAvailableActions();
    }

    /**
     * Setup CLI arguments for the command.
     *
     * @return void
     */
    protected function setup(): void
    {
        $this->addArgument("action", InputArgument::REQUIRED);
        $this->addArgument("params", InputArgument::OPTIONAL);
    }

    /**
     * Process the CLI command input and execute the selected action.
     *
     * @param InputInterface $input CLI input interface
     * @param OutputInterface $output CLI output interface
     * @param SymfonyStyle $io Symfony style helper
     * @return void
     */
    protected function process(InputInterface $input, OutputInterface $output, SymfonyStyle $io): void
    {
        try
        {
            $action = $input->getArgument('action');

            if (!in_array($action, array_keys($this->availableActions)))
            {
                throw new \Exception("The selected action is not allowed. Select the correct action name.");
            }

            call_user_func_array($this->availableActions[$action], [$input, $output, $io]);
        }
        catch (\Exception $e)
        {
            $io->error($e->getMessage());
        }
    }

    /**
     * Initialize available CLI actions for the command.
     *
     * @return void
     */
    protected function initAvailableActions(): void
    {
        $this->availableActions[self::ACTION_LIST] = function ($input, $output, $io) {
            $this->listAction($input, $output, $io);
        };

        $this->availableActions[self::ACTION_RUN] = function ($input, $output, $io) {
            $this->runAction($input, $output, $io);
        };
    }

    /**
     * List all available upgrade files.
     *
     * @param InputInterface $input CLI input interface
     * @param OutputInterface $output CLI output interface
     * @param SymfonyStyle $io Symfony style helper
     * @return void
     */
    protected function listAction($input, $output, $io): void
    {
        $patchManager = new PatchManager();

        $upgrades = $patchManager->getUpdateFiles();

        if (empty($upgrades))
        {
            $io->info('No upgrades available');
            return;
        }

        $headers = [new TableCell('Available upgrades', ['colspan' => 1])];

        $rows = array_map(function ($value) {
            return [$value];
        }, array_keys($upgrades));

        $io->table($headers, $rows);
    }

    /**
     * Run the upgrade for the specified version.
     *
     * @param InputInterface $input CLI input interface
     * @param OutputInterface $output CLI output interface
     * @param SymfonyStyle $io Symfony style helper
     * @return void
     * @throws \Exception If version is not specified
     */
    protected function runAction($input, $output, $io): void
    {
        $version = $input->getArgument('params');

        if (empty($version))
        {
            throw new \Exception("Version not specified");
        }

        $io->info(sprintf('Run upgrade for version: %s', $version));

        Addon::upgrade(['version' => $version], true);

        $io->success('Upgrade completed successfully');
    }

}