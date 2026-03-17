<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductCustomFields;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\FirewallConstants;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Tenant;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;

class FirewallManager
{
    const RULE_CUSTOM = 'custom';
    const FIREWALL_SECURITY_GROUP_ID = 'firewallSecurityGroupID';

    /**
     * @var array
     */
    protected $params;

    /**
     * @var Tenant
     */
    protected $tenant;

    /**
     * @var ProductConfiguration
     */
    protected ?ProductConfiguration $productConfig;

    /**
     * @var ProductCustomFields
     */
    protected ?ProductCustomFields $productCustomFields;

    /**
     * @var string
     */
    protected $vmID;

    /**
     * Firewall constructor.
     * @param $params
     */
    public function __construct($params)
    {
        $this->params        = $params;
        $this->vmID          = $this->params['customfields']['vmID'] ?: null;
        $this->productConfig = new ProductConfiguration($this->params['serviceid']);
        $this->productCustomFields = new ProductCustomFields($this->params['pid'], $this->params['serviceid']);
        $this->tenant        = Factory::getTenantAsUser($this->params, $this->productConfig->getTenantID());
    }

    /**
     * @param array $postData
     * @throws Exception
     */
    public function addNewFirewallRule(array $postData)
    {
        $this->checkIfUserCanCreateFirewallRule($postData['direction']);

        Api::getInstance()->network()->addNewSecurityRule($postData);
    }

    /**
     * @throws Exception
     */
    protected function checkIfUserCanCreateFirewallRule(string $direction)
    {
        $groupID        = $this->getSecurityGroupID();
        $existingGroups = $this->getSecurityRules($groupID);
        $groupsLimit    = $this->productConfig->getTotalRulesLimit();


        /**
         * Check if total limit is reached
         */
        if ($groupsLimit && $groupsLimit <= count($existingGroups))
        {
            throw new \Exception('You can\'t create firewall rule due to limit reached');
        }

        /**
         * Check if selected direction rules limit is reached
         */
        $inboundRulesLimit  = $this->productConfig->getInboundRulesLimit();
        $inbound            = 0;
        $outboundRulesLimit = $this->productConfig->getOutboundRulesLimit();
        $outbound           = 0;

        foreach ($existingGroups as $group)
        {
            $group['direction'] == FirewallConstants::DIRECTION_INGRESS ? $inbound++ : $outbound++;
        }

        if (($direction == FirewallConstants::DIRECTION_INGRESS && $inboundRulesLimit && $inboundRulesLimit <= $inbound) ||
            ($direction == FirewallConstants::DIRECTION_EGRESS && $outboundRulesLimit && $outboundRulesLimit <= $outbound))
        {
            throw new \Exception('You can\'t create rule with this direction due to limit reached');
        }

        return;
    }

    public function getSecurityGroupID()
    {
        return $this->productCustomFields->getCustomFieldsValue(self::FIREWALL_SECURITY_GROUP_ID);
    }

    public function getSecurityRules($groupID)
    {
        $network = Api::getInstance()->network()->getSecurityRules($groupID);
        return $network['security_group']["security_group_rules"];
    }

    public function deleteFirewallRules(array $rulesIDs)
    {
        foreach ($rulesIDs as $ruleID)
        {
            Api::getInstance()->network()->removeSecurityRule($ruleID);
        }
    }

    public function securityGroupExists(string $existingGroupID)
    {
        try
        {
            $existingGroupData = Api::getInstance()->network()->getSecurityGroup($existingGroupID);
            //preg match (vmId + timestamp)
            if (isset($existingGroupData['security_group']) && preg_match('/^' . $this->vmID . '[0-9]+/', $existingGroupData['security_group']['name'])) {
                return true;
            }
        }
        catch(\Exception $e)
        {

        }

        return false;
    }

    public function createSecurityGroup()
    {
        //get from DB
        $existingGroupID = $this->getSecurityGroupID();
        //check if exists for VM in openstack
        if($existingGroupID && $this->securityGroupExists($existingGroupID))
        {
            return $existingGroupID;
        }

        //create a new one
        $groupName = $this->vmID . time();
        $network = Api::getInstance()->network()->createSecurityGroup($groupName, $this->productConfig->getTenantID());
        if (!$network['security_group']['id']) {
            throw new \Exception('Unable to create new security group!');
        }

        $this->productCustomFields->updateFieldValue(self::FIREWALL_SECURITY_GROUP_ID, $network['security_group']['id']);

        $this->assignGroupToVM($groupName);
        return $network['security_group'];
    }

    public function assignGroupToVM($groupName): void
    {
        Api::getInstance()->compute()->assignSecurityGroupVPS($this->vmID, $groupName);
    }

    public function unassignGroupFromVm(): void
    {
        Api::getInstance()->compute()->unassignSecurityGroupVPS($this->params['customfields']['vmID'], $this->getSecurityGroupID());
    }

    public function isGroupAssignedToVm(): bool
    {
        $groupId = $this->getSecurityGroupID();
        foreach ($this->getGroupList() as $group)
        {
            if ($group['id'] == $groupId)
            {
                return true;
            }
        }

        return false;
    }

    public function ensureGroupUnassigned(): void
    {
        if (!$this->isGroupAssignedToVm())
        {
            return;
        }

        $this->unassignGroupFromVm();

    }

    public function ensureGroupDeleted(): void
    {
        if (!$this->groupExists())
        {
            return;
        }

        $this->deleteSecurityGroup($this->getSecurityGroupID());
    }

    public function groupExists(): bool
    {
        $groupId = $this->getSecurityGroupID();
        if (empty($groupId))
        {
            return false;
        }

        $group = Api::getInstance()
            ->network()
            ->getSecurityGroup($groupId);

        return Arr::get($group, 'security_group.id') == $groupId;

    }

    public function deleteSecurityGroup(string $groupId)
    {
        Api::getInstance()->network()->deleteSecurityGroup($groupId);

        $this->productCustomFields->updateFieldValue(self::FIREWALL_SECURITY_GROUP_ID, '');
    }

    public function addAdditionalRules($groupID)
    {
        $this->deleteAllRules($groupID);

        foreach (Config::get('additionalRules.rules', []) as $rule)
        {
            $rule = (object)$rule;
            $rule->security_group_id = $groupID;
            try
            {
                Api::getInstance()->network()->addNewSecurityRule($rule);
            }
            catch (\Exception $e)
            {
                continue;
            }
        }

    }

    public function deleteAllRules($groupID)
    {
        $ruleList = $this->getSecurityRules($groupID);
        foreach ($ruleList as $rule)
        {
            Api::getInstance()->network()->removeSecurityRule($rule['id']);
        }
    }

    /**
     * @return array|null
     * @throws \Exception
     */
    public function getGroupList()
    {
        $list = Api::getInstance()->compute()->getSecurityGroupList($this->vmID);
        return $list['security_groups'];
    }
}
