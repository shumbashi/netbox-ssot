<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Http\Actions;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\UsernameDecorator;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\CreationVm;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders\ImageBuilder;
use ModulesGarden\OpenStackVpsCloud\App\Models\Job;
use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Services\AppConfiguration;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Status;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Facades\Queue;
use Illuminate\Database\Capsule\Manager as DB;

/**
 * Class CreateAccount
 *
 * @author <slawomir@modulesgarden.com>
 */
class CreateAccount extends AddonController
{
    const CREATE_ACCOUNT = 'create';

    protected $params = [];


    public function execute($params = null)
    {
        $this->params = $params;

        if (JobManager::isBuilding($params['serviceid'])) {
            return 'This task already exists';
        }

        if ($this->params['customfields']['vmID'])
        {
            return 'Custom Field /VM ID/ is not empty';
        }


        $app = (new AppConfiguration($this->params['serviceid']))
            ->getApp();

        /*Attempt to build image*/
        try {
            (new ImageBuilder($this->params))->buildFromApp($app);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $this->generateDomainIfEmpty($this->params['domain']);

        try {
            Job::byServiceID($this->params['serviceid'])
                ->whereIn('status', [Status::WAITING, Status::PENDING, Status::RUNNING, Status::ERROR, ''])
                ->update(['status' => Status::CANCELLED]);

            $this->validateIPs();
            Queue::push(CreationVm::class,
                [
                    'hid'     => $this->params['serviceid'],
                    'pid'     => $this->params['pid'],
                ],
                'default',
                null,
                'Hosting',
                $this->params['serviceid']);

            $this->generateUsername($app->getConfigArray());

            return 'success';

        }
        catch (\Exception $exception)
        {
            return $exception->getMessage();
        }
    }

    private function validateIPs()
    {
        $productConfig = new ProductConfiguration($this->params['serviceid']);
        if(!$productConfig->getIps())
        {
            throw new \Exception('At least one IP address is required to create a Fixed Network');
        }
    }

    /**
     * @param string|null $domain
     */
    private function generateDomainIfEmpty(string $domain = null)
    {
        if (!empty($domain))
        {
            return;
        }
        $productConfig = new ProductConfiguration($this->params['serviceid']);
        $this->params['domain'] = !empty($productConfig->getCustomFields()['domain']) ? $productConfig->getCustomFields()['domain'] : $productConfig->getRandomDomainPrefix() . uniqid((string)$this->params['serviceid']);

        $service = Service::find($this->params['serviceid']);
        $service->domain = $this->params['domain'];
        $service->save();

    }

    private function generateUsername(?array $appConfig)
    {
        $username = UsernameDecorator::decorate($appConfig);
        DB::statement("UPDATE tblhosting SET username = ? WHERE id = ?", [$username, $this->params['serviceid']]);
    }
}

