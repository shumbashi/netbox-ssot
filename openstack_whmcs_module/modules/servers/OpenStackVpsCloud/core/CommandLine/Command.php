<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\CommandLine;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Command
 *
 * Final implementation of AbstractCommand that locks down the configure and execute methods
 * to ensure consistent command behavior across the framework.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\CommandLine
 */
class Command extends AbstractCommand
{
    /**
     * Final implementation of configure to prevent override.
     *
     * @return void
     */
    final protected function configure()
    {
        parent::configure();
    }

    /**
     * Final implementation of execute to prevent override.
     *
     * @param InputInterface $input The input interface instance
     * @param OutputInterface $output The output interface instance
     * @return int Command exit code
     */
    final protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return parent::execute($input, $output);
    }
}
