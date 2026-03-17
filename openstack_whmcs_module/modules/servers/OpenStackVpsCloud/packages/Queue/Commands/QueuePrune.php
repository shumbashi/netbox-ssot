<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Commands;

use ModulesGarden\OpenStackVpsCloud\Packages\CommandLine\Commands\SupervisedCommand;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Services\AutoPrune;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class Queue
 */
class QueuePrune extends SupervisedCommand
{
    /**
     * Command description
     * @var string
     */
    protected $description = 'Removes tasks older than a specified number of days from the queue';

    /**
     * Command help text
     * @var string
     */
    protected $help = 'Just run that command to start removing old tasks from the queue!';

    /**
     * Command name
     * @var string
     */
    protected $name = 'queue:prune';

    /**
     * Configure command
     */
    protected function setup()
    {
    }

    /**
     * Run your custom code
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function process(InputInterface $input, OutputInterface $output, SymfonyStyle $io)
    {
        (new AutoPrune())->run();
    }
}
