<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Configuration\Addon\Activate;

/**
 * Runs before module activation actions
 */
class Before extends \ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\Activate\Before
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
