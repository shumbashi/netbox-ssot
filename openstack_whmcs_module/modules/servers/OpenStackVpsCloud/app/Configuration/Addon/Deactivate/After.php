<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Configuration\Addon\Deactivate;

/**
 * Runs after addon deactivation
 */
class After extends \ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\Deactivate\After
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
