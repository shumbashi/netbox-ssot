<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Commands;

use ModulesGarden\OpenStackVpsCloud\Packages\CommandLine\Commands\CommandLoop;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\Job;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class Queue
 */
class QueueLoop extends CommandLoop
{
    /**
     * Command description
     * @var string
     */
    protected $description = 'Run module queue in loop';

    /**
     * Command help text
     * @var string
     */
    protected $help = 'Just run that command to start all queued tasks!';

    /**
     * Command name
     * @var string
     */
    protected $name = 'queue:loop';

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
        $queue = new \ModulesGarden\OpenStackVpsCloud\Packages\Queue\Queue();
        $queue->setCallBefore(function(Job $job) use ($io) {
            $io->writeln("Running {$job->job}");
        });
        $queue->process();
    }
}
