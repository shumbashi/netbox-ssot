<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Helpers;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use function ModulesGarden\OpenStackVpsCloud\Core\Helper\isAdmin;

class FeaturesHelper {
    public static function getEnabledFeatures()
    {
        $productConfig = (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration(Params::get('packageid')))->get();

        if (isAdmin()) {
            return array_filter($productConfig['admin_rows'] ?? []);
        }

        return array_filter($productConfig['client_rows'] ?? []);
    }

}