<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers;


use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Api\OpenStackVPS\ComputeApiService;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;

class BaseVpsManager
{
    /**
     * @var VPSModel
     */
    protected $vm;

    /**
     * @var array
     */
    protected $whmcsParams;

    /**
     * @var ProductConfiguration
     */
    protected $productConfig;

    /**
     * BaseVpsManager constructor.
     * @param string $vmID
     * @param array $params
     */

    protected ?string $vmId;


    //TODO: refactor this to load vm and reinstaciate token ONLY WHEN NECESSARY
    public function __construct(string $vmID, array $params = [])
    {
        $this->whmcsParams = $params;

        if (empty($this->whmcsParams))
        {
            $this->whmcsParams = WhmcsParamsHelper::getWhmcsParamsByVmId($vmID);
        }

        $this->productConfig = new ProductConfiguration($this->whmcsParams['serviceid']);
        $this->vmId = $vmID;
        $this->loadVM($vmID);
    }

    protected function loadVM(string $vmID)
    {
        $tenant = Factory::getTenantAsUser($this->whmcsParams, $this->productConfig->getTenantID());

        $this->vm = $tenant->VPS($vmID);
    }
}