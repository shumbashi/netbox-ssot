<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\CommandLine;

use ModulesGarden\OpenStackVpsCloud\Core\CommandLine\LoadCommands\LoadCommandsControllers;
use ModulesGarden\OpenStackVpsCloud\Core\CommandLine\LoadCommands\LoadCoreCommandsController;
use ModulesGarden\OpenStackVpsCloud\Core\CommandLine\LoadCommands\LoadCommandsControllersInterface;
use ModulesGarden\OpenStackVpsCloud\Core\CommandLine\LoadCommands\LoadCommandsControllersPackageServices;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Application
 *
 * Main console application for registering and running command providers.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\CommandLine
 */
class Application extends \Symfony\Component\Console\Application
{
    /**
     * Directory for command discovery.
     *
     * @var string
     */
    protected $dir = '';

    /**
     * Run the console application and register command providers.
     *
     * @param InputInterface|null $input
     * @param OutputInterface|null $output
     * @return int|void
     */
    public function run(?InputInterface $input = null, ?OutputInterface $output = null)
    {
        $this->addCommandsProvider(new LoadCommandsControllers);
        $this->addCommandsProvider(new LoadCoreCommandsController);
        $this->addCommandsProvider(new LoadCommandsControllersPackageServices);

        parent::run();
    }

    /**
     * Add commands from a provider implementing LoadCommandsControllersInterface.
     *
     * @param LoadCommandsControllersInterface $provider
     * @return void
     */
    public function addCommandsProvider(LoadCommandsControllersInterface $provider)
    {
        $commands = $provider->getCommands($this->dir);

        foreach ($commands as $command)
        {
            $this->add(new $command);
        }
    }
}
