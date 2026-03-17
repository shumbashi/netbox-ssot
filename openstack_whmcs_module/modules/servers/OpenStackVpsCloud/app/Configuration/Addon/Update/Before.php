<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Configuration\Addon\Update;

/**
 * runs before module update actions
 */
class Before extends \ModulesGarden\OpenStackVpsCloud\Core\Configuration\Addon\Update\Before
{
    /**
     * @return array
     */
    public function execute($version)
    {
        $return = parent::execute($version);

        return $return;
    }
}
