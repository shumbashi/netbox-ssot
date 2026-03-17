<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Http\Actions;

use ModulesGarden\OpenStackVpsCloud\App\Http\Admin\Home;
use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\AddonController;

/**
 * Class AdminServicesTabFields
 *
 * @author <slawomir@modulesgarden.com>
 */
class AdminServicesTabFields extends AddonController
{
    public function execute($params = null)
    {
        return [Home::class, 'productIndex'];
    }
}