<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Configuration\Addon\Config;

/**
 * Runs before loading module configuration
 */
class Before extends \ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\Config\Before
{
    /**
     * @param array $params
     * @return array
     */
    public function execute(array $params = [])
    {
        $return = parent::execute($params);

        return $return;
    }
}
