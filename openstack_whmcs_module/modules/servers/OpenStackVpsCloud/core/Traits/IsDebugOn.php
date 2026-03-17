<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Traits;

use ModulesGarden\OpenStackVpsCloud\Core\ServiceLocator;

/**
 * Trait IsDebugOn
 *
 * Provides debug mode detection functionality for classes.
 * This trait is deprecated and should not be used in new code.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Traits
 * @deprecated Use configuration facade directly instead
 */
trait IsDebugOn
{
    /**
     * Check if debug mode is enabled.
     *
     * @return bool True if debug mode is enabled, false otherwise
     * @deprecated Use \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('configuration.debug') directly
     */
    public function isDebugOn()
    {
        return \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('configuration.debug');
    }
}
