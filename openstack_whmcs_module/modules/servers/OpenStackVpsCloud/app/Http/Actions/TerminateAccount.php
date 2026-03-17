<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Http\Actions;

use ModulesGarden\OpenStackVpsCloud\App\Jobs\TerminatingAccount;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\DeleteDetails;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\TerminationVM;
use ModulesGarden\OpenStackVpsCloud\App\Models\Backups;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\FirewallManager;
use ModulesGarden\OpenStackVpsCloud\App\Models\Job;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Status;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Facades\Queue;

class TerminateAccount extends CreateAccount
{

    public function execute($params = null)
    {
        Job::byServiceID($params['serviceid'])
            ->whereIn('status', [Status::WAITING, Status::PENDING, Status::RUNNING, Status::ERROR, ''])
            ->update(['status' => Status::CANCELLED]);

        if (!$params['customfields']['vmID'])
        {
            return 'Custom Field VM ID is empty';
        }

        try {
            $productConfig = new ProductConfiguration($params['serviceid']);
            Backups::where('sourceID', '=', $params['customfields']['vmID'])->delete();
            $this->deleteSecurityGroupIfExist($params);
            Queue::push(TerminatingAccount::class,
                [
                    'hid' => $params['serviceid'],
                    'pid' => $params['pid'],
                ],
                'default',
                null,
                'Hosting',
                $params['serviceid']);

            if($productConfig->getClearVmDetails() == true)
            {
                Queue::push(DeleteDetails::class,
                    [
                        'hid' => $params['serviceid'],
                        'pid' => $params['pid']
                    ],
                    'default',
                    null,
                    'Hosting',
                    $params['serviceid']);
            }

            return 'success';
        }
        catch (\Exception $exception)
        {
            return $exception->getMessage();
        }

    }


    /**
     * @param array $params
     */
    protected function deleteSecurityGroupIfExist(array $params)
    {
        try {
            $firewall = new FirewallManager($params);
            $groupID  = $firewall->getSecurityGroupID();

            if ($groupID)
            {
                $firewall->ensureGroupUnassigned();
                $firewall->ensureGroupDeleted();
            }
        }
        catch (\Exception $exception) {

        }

    }

}
