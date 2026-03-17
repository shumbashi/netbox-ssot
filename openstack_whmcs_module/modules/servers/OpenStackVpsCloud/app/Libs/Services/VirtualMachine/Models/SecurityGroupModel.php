<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models;

use Exception;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Api\OpenStackVPS\ComputeApiService;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;

/**
 * Class SecurityGroupModel
 * @package ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models
 */
class SecurityGroupModel extends BaseVpsModel
{
    const NAME_DEFAULT = 'default';

    /**
     * @var string
     */
    protected $name;

    /**
     * SecurityGroup constructor.
     * @param string|null $tenantID
     * @param string|null $UUID
     * @param array $params
     * @throws Exception
     */
    public function __construct(string $tenantID = null, string $UUID = null, array $params = [])
    {
        if (empty($params) && $UUID)
        {
            Api::getInstance()->network()->getSecurityGroup($UUID);
        }

        parent::__construct($tenantID, $UUID, $params);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function listSource()
    {
        return Api::getInstance()->network()->listSecurityGroups([
            'project_id' => $this->tenantID
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }
}