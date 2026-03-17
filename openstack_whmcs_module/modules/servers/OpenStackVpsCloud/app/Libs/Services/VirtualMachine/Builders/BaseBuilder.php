<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders;


use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductCustomFields;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\CacheResources\FlavorCacheModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\CacheResources\ImageCacheModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\CacheResources\NetworkCacheModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Tenant;
use ModulesGarden\OpenStackVpsCloud\App\Models\Servers;

class BaseBuilder
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var ProductConfiguration
     */
    protected $productConfig;

    /**
     * @var ProductCustomFields
     */
    protected $productCustomFields;

    /**
     * @var Tenant
     */
    protected $tenant;

    /**
     * BaseBuilder constructor.
     * @param array $params
     * @throws \Exception
     */
    public function __construct(array $params)
    {
        $this->params              = $params;
        $this->productConfig       = new ProductConfiguration($params['serviceid']);
        $this->productCustomFields = new ProductCustomFields($params['pid'], $params['serviceid']);
        $this->tenant              = Factory::getTenantAsUser($this->params, $this->productConfig->getTenantID());
    }

    /**
     * @param string $idOrName
     * @param string $service (flavor, network, image)
     * @param string $serviceSetting
     * @param array $listOfResources
     * @param string|null $customFieldFriendlyName
     * @return string|null
     * @throws Exception
     */
    protected function getServiceIdFromSelectedRegionResources(string $idOrName, string $service, string $serviceSetting, array $listOfResources, string $customFieldFriendlyName = null)
    {
        $serviceName = $this->getResourceName($idOrName, $customFieldFriendlyName, $serviceSetting);

        /**
         *  Get service ID by service name from list of available services in selected region.
         *
         *  The module assumes that all regions have identical services names.
         */
        foreach ($listOfResources as $resource)
        {
            if ($resource['name'] == $serviceName)
            {
                return $resource['id'];
            }
        }

        /**
         * Throw exception if selected service is not available in selected region
         */
        throw new \Exception(sprintf('%s %s is not available in region %s.', ucfirst($service), $idOrName, $this->productConfig->getRegion()));
    }

    /**
     * Get resource name from saved cache
     *
     * @param string $searchedValue (id or name of selected resource)
     * @param string|null $customFieldFriendlyName
     * @param string $serviceSettingName
     * @return string|null
     */
    protected function getResourceName(string $searchedValue, ?string $customFieldFriendlyName, string $serviceSettingName)
    {
        /**
         * Resources saved in cache (from only one region)
         */
        $serverModel        = new Servers();
        $availableResources = $serverModel->getEndpoint($this->params['serverid'], $serviceSettingName);

        /**
         * @var $resourceModel FlavorCacheModel|ImageCacheModel|NetworkCacheModel
         */
        foreach ($availableResources as $resourceModel)
        {
            $resourceId = $resourceModel->getUUID();

            if ($searchedValue === $resourceId ||
                $searchedValue === $resourceModel->getName() ||
                $customFieldFriendlyName === $resourceId ||
                $customFieldFriendlyName === $resourceModel->getName())
            {
                return $resourceModel->getName();
            }
        }

        return null;
    }

}