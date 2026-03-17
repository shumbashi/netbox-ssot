<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Addon;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AddonControllerInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Module\Addon;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\LogActivity;

class Deactivate extends \ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\AddonController implements AddonControllerInterface
{
    public function execute($params = []): array
    {
        try
        {
            Addon::deactivate($params);

            return ['status' => 'success'];
        }
        catch (\Throwable $exc)
        {
            LogActivity::error($exc->getMessage());

            return [
                'status'      => 'error',
                'description' => $exc->getMessage(),
            ];
        }
    }
}
