<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\PostCreationSetters;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums\LoggerMessages;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\FirewallManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\SecurityGroupManager;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Facades\Logger;

class PostChangePackageSetter extends BaseSetter
{
    public function setSecurityGroups(): void
    {
        $this->params['customfields']['vmID'] = $this->vm->getUUID();

        $newSecurityGroups = $this->productConfig->getSecurityGroups();

        $firewallGroupId = $this->productCustomFields->getCustomFieldsValue(FirewallManager::FIREWALL_SECURITY_GROUP_ID);
        $firewallEnabled = $this->productConfig->getCafFirewall() || $this->productConfig->getAafFirewall();
        $firewallManager = new FirewallManager($this->params);

        if (!$firewallEnabled && !empty($firewallGroupId)) {
            $firewallManager->deleteAllRules($firewallGroupId);
            $firewallManager->ensureGroupUnassigned();

            try {
                $firewallManager->ensureGroupDeleted();
            }
            catch (\Exception $e) {
                Logger::error(LoggerMessages::EXCEPTION, [
                    'service' => $this->params['serviceid'],
                    'message' => $e->getMessage()
                ]);
            }
        }
        elseif ($firewallEnabled && empty($firewallGroupId))
        {
            $firewallGroup = $firewallManager->createSecurityGroup();
            if ($this->productConfig->getCafAdditionalRules() && isset($firewallGroup['id']))
            {
                $firewallManager->addAdditionalRules($firewallGroup['id']);
            }

            $newSecurityGroups[] = $firewallGroup['id'];
        }
        else {
            $newSecurityGroups[] = $firewallGroupId;
        }

        $securityGroupsManager = new SecurityGroupManager($this->vm->getUUID(), $this->productConfig->getTenantId());
        $securityGroupsManager->change($newSecurityGroups);
    }
}