<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\Update;

use ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\AbstractAfter;

/**
 * runs after module update actions
 */
class After extends AbstractAfter
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
