<?php

namespace ModulesGarden\OpenStackVpsCloud\packages\Product\Integrations;

use ModulesGarden\OpenStackVpsCloud\Core\Hook\AbstractHookIntegrationController;
use ModulesGarden\OpenStackVpsCloud\Core\Hook\Integration\Enums\IntegrationTypes;
use ModulesGarden\OpenStackVpsCloud\Core\Hook\Integration\Integration;
use ModulesGarden\OpenStackVpsCloud\Core\Hook\Integration\Models\ControllerCallback;
use ModulesGarden\OpenStackVpsCloud\Core\Hook\Integration\Models\RelatedField;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Http\Admin\ServerConfig;

class ServerConfigurationAlertIntegration extends AbstractHookIntegrationController
{
    public function __construct()
    {
        $this->setIntegration(
            (new Integration(new ControllerCallback(ServerConfig::class, 'alert'), '#newHash'))
                ->requireFile('configservers')
                ->requireGetFields(['action' => 'manage'])
                ->setType(IntegrationTypes::After)
                ->dependentOnFormField(new RelatedField("#inputServerType", [ModuleConstants::getModuleName()]))
        );
    }
}


