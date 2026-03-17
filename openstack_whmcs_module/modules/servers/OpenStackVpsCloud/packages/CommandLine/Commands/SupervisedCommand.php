<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\CommandLine\Commands;

use ModulesGarden\OpenStackVpsCloud\Core\CommandLine\Command;
use ModulesGarden\OpenStackVpsCloud\Packages\CommandLine\Hypervisor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SupervisedCommand extends Command
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    protected function beforeProcess(InputInterface $input, OutputInterface $output)
    {
        (new Hypervisor($this->getName(), $input->getOptions()))
            ->lock();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function afterProcess(InputInterface $input, OutputInterface $output)
    {
        (new Hypervisor($this->getName(), $input->getOptions()))
            ->unlock();
    }
}
