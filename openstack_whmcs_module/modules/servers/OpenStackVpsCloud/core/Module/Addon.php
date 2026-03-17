<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Module;

use ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\Activate\After;
use ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\Activate\Before;
use ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\Update\PatchManager;
use ModulesGarden\OpenStackVpsCloud\Core\Database\FileLoader;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\AfterModuleActivated;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\AfterModuleUpgraded;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ModuleActivated;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ModuleDeactivated;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ModuleUpgraded;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\PreModuleActivated;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\PreModuleUpgraded;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\ServiceLocator;
use function ModulesGarden\OpenStackVpsCloud\Core\fire;

/**
 * Class Addon
 *
 * Handles module activation, deactivation, and upgrade lifecycle events.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Module
 */
class Addon
{
    /**
     * Activate the module.
     *
     * Executes pre-activation hooks, loads database schema/data, runs post-activation hooks, and fires activation events.
     *
     * @param array $params Optional activation parameters
     * @return void
     */
    static function activate(array $params = []): void
    {
        fire(PreModuleActivated::class);

        //Before module activation
        ServiceLocator::call(Before::class)->execute($params);

        //Module Activation
        $fileLoader = new FileLoader();
        $fileLoader->performQueryFromFile(ModuleConstants::getFullPath('app', 'Database', 'schema.sql'));
        $fileLoader->performQueryFromFile(ModuleConstants::getFullPath('app', 'Database', 'data.sql'));

        //After module activation
        ServiceLocator::call(After::class)->execute($params);

        fire(ModuleActivated::class);
        fire(AfterModuleActivated::class);
    }

    /**
     * Deactivate the module.
     *
     * Executes pre- and post-deactivation hooks and fires deactivation events.
     *
     * @param array $params Optional deactivation parameters
     * @return void
     */
    static function deactivate(array $params = []): void
    {
        // Before module deactivation
        ServiceLocator::call(\ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\Deactivate\Before::class)->execute($params);

        // After module deactivation
        ServiceLocator::call(\ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\Deactivate\After::class)->execute($params);

        fire(ModuleDeactivated::class);
    }

    /**
     * Upgrade the module.
     *
     * Executes pre-upgrade hooks, runs patch manager, executes post-upgrade hooks, and fires upgrade events.
     *
     * @param array $params Upgrade parameters (must include 'version')
     * @param bool $force Whether to force upgrade
     * @return void
     * @throws \Exception If no version is specified
     */
    static function upgrade(array $params, bool $force = false): void
    {
        fire(PreModuleUpgraded::class);

        // Before module upgrade
        ServiceLocator::call(\ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\Update\Before::class)->execute($params);

        //Module Upgrade
        if (empty($params['version']))
        {
            throw new \Exception('No version specified');
        }

        (new PatchManager())->run($params['version'], $force);

        // After module upgrade
        ServiceLocator::call(\ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\Update\After::class)->execute($params);

        fire(ModuleUpgraded::class);
        fire(AfterModuleUpgraded::class);
    }
}