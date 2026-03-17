<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Http\Actions;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\Actions\SuspendAccManager;

/**
 * Class SuspendAccount
 *
 * @author <slawomir@modulesgarden.com>
 */
class SuspendAccount extends TerminateAccount
{

    public function execute($params = null)
    {
        if (!$params['customfields']['vmID'])
        {
            return 'Custom Field /VM ID/ is empty';
        }

        try {
            $suspendAccManager = new SuspendAccManager($params);
            $suspendAccManager->suspend();

            return 'success';
        }
        catch (\Exception $exception)
        {
            return 'ERROR: ' . $exception->getMessage();
        }
    }

}
