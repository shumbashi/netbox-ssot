<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Http\Actions;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\Actions\UnsuspendAccManager;

class UnsuspendAccount extends TerminateAccount
{
    public function execute($params = null)
    {
        if (!$params['customfields']['vmID'])
        {
            return 'Custom Field /VM ID/ is empty';
        }

        try {
            $unsuspendAccManager = new UnsuspendAccManager($params);
            $unsuspendAccManager->unsuspend();

            return 'success';
        }
        catch (\Exception $exception)
        {
            return 'ERROR: ' . $exception->getMessage();
        }
    }

}
