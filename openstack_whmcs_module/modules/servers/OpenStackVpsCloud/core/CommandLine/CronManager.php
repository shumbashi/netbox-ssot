<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\CommandLine;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CronManager
 *
 * Manages the registration and execution of cron job commands.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\CommandLine
 */
class CronManager extends Application
{
    /**
     * Directory containing cron job classes.
     *
     * @var string
     */
    protected $dir = 'Cron';
}
