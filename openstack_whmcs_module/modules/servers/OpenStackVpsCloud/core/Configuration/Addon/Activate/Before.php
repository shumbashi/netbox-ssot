<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\Activate;

use ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\AbstractBefore;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\ServiceLocator;

/**
 * Runs before module activation actions
 */
class Before extends AbstractBefore
{
    /**
     * @param array $params
     * @return array
     */
    public function execute(array $params = [])
    {
        return $params;
    }
}
