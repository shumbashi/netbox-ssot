<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Http\Admin;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\PreServerConfigurationLoaded;
use ModulesGarden\OpenStackVpsCloud\Core\Helper;
use ModulesGarden\OpenStackVpsCloud\Core\Http\AbstractController;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Enums\ConfigSettings;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Alerts\ServerConfigurationAlert;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Forms\ServerConfigurationProxy;
use function ModulesGarden\OpenStackVpsCloud\Core\fire;

class ServerConfig extends AbstractController implements AdminAreaInterface
{
    public function index()
    {
        fire(new PreServerConfigurationLoaded());

        if (!Config::get(ConfigSettings::PRODUCT_SERVER_CONFIG_FORM)) {
            return;
        }

        return Helper\viewIntegrationAddon()
            ->addElement(ServerConfigurationProxy::class);
    }

    public function alert()
    {
        if (!Config::get(ConfigSettings::PRODUCT_SERVER_CONFIG_FORM)) {
            return;
        }

        return Helper\viewIntegrationAddon()
            ->addElement(new ServerConfigurationAlert());
    }
}
