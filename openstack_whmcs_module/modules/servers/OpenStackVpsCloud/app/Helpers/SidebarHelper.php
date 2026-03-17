<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Helpers;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration;

class SidebarHelper
{
    protected ?array $productConfiguration = [];

    const PERMISSIONS = [
        'reinstallation' => 'caf_rebuild',
        'backups' => 'caf_backups',
        'console' => 'caf_console',
        'firewall' => 'caf_firewall',
        'snapshots' => 'caf_snapshots',
    ];

    public function __construct()
    {
        $this->loadConfiguration();
    }

    public function isEnabled(string $panelName, string $sidebarItemName): bool
    {
        if (Config::get("product.sidebars.$panelName.$sidebarItemName.enabled")) {
            return true;
        }

        if (!isset(self::PERMISSIONS[$sidebarItemName])) {
            return false;
        }

        if (!isset($this->productConfiguration[self::PERMISSIONS[$sidebarItemName]]))
        {
            return false;
        }

        return (bool)$this->productConfiguration[self::PERMISSIONS[$sidebarItemName]];
    }

    public function loadConfiguration(): void
    {
        $service = Service::select('packageid')->where('id', Request::get('id', 0))->first();
        if ($service) {
            $this->productConfiguration = (new ProductConfiguration($service->packageid))->get();
        }
    }
}