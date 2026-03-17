<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\CommandLine\Commands;

use Exception;
use ModulesGarden\OpenStackVpsCloud\Core\CommandLine\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandLoop extends AbstractCommand
{
    /**
     * Loop interval in seconds
     * @var int
     */
    protected $loopInterval = 5;

    final protected function configure()
    {
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws Exception
     */
    final protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $hypervisor = new Hypervisor($this->getName(), $input->getOptions());

        /**
         * Lock command in database. We want to run only one command in the same time
         */
        $hypervisor->lock();

        do
        {
            /**
             * Let's do something funny!
             */
            parent::execute($input, $output);

            /**
             * Ping... Pong... Ping...
             */
            $hypervisor->ping();

            /**
             * Time to sleep!
             */
            $hypervisor->sleep($this->loopInterval);
        } while (!$hypervisor->shouldBeStopped());

        /**
         * Unlock command in database
         */
        $hypervisor->unlock();

        return 0;
    }
}

//
