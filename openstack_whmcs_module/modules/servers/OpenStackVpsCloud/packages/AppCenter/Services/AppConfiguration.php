<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Services;

use Exception;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums\AppProductConfig;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\App;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\Factories\AppModelFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Group;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\Configuration\ConfigurationContainer;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Support\Factories\ConfigurationFactory;

class AppConfiguration
{
    protected ?int $serviceId = null;
    protected ?ConfigurationContainer $configuration = null;

    public function __construct(int $serviceId)
    {
        $this->serviceId     = $serviceId;
        $this->configuration = ConfigurationFactory::fromService($serviceId);
    }

    public function getApp(): ?App
    {
        $item    = Item::find($this->getAppItemId());
        $service = Service::find($this->serviceId);

        if (!$service)
        {
            return null;
        }

        return AppModelFactory::forServiceItem($service, $item);
    }

    public function getAppItemId(): ?int
    {
        $id = $this->configuration->getConfig(AppProductConfig::APP_DROPDOWN_NAME);
        if (empty($id))
        {
            throw new Exception('App is not configured');
        }

        if (!Item::where('id', $id)->exists())
        {
            throw new Exception('App does not exist');
        }

        return $id;
    }

    /**
     * @return int|null
     * @throws Exception
     * @deprecated use getAppGroupIds() instead
     */
    public function getAppGroupId(): ?int
    {
        return $this->getAppGroupIds()[0];
    }

    public function getAppGroupIds(): array
    {
        $ids = $this->configuration->getConfig(AppProductConfig::GROUP_DROPDOWN_NAME);
        if (!is_array($ids))
        {
            $ids = [$ids];
        }

        if (empty($ids))
        {
            throw new Exception('App group not configured');
        }

        if (Group::whereIn('id', $ids)->count() != count($ids))
        {
            throw new Exception('App group does not exist');
        }

        return $ids;
    }
}