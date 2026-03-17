<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\Actions;

use Exception;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductCustomFields;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Tenant;

class BaseManager
{
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
     * BaseManager constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params              = $params;
        $this->productConfig       = new ProductConfiguration($params['serviceid']);
        $this->productCustomFields = new ProductCustomFields($this->params['pid'], $this->params['serviceid']);
    }

    /**
     * @return VPSModel
     * @throws OSException
     */
    protected function loadVm()
    {
        $tenant = $this->loadTenant();
        $vmID   = $this->params['customfields']['vmID'];
        return $tenant->VPS($vmID);
    }

    /**
     * @return Tenant
     * @throws Exception
     */
    protected function loadTenant()
    {
        return Factory::getTenantAsUser($this->params, $this->productConfig->getTenantID());
    }
}