<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models;

class Product extends \WHMCS\Product\Product
{
    /**
     * @deprecated - use productGroup
     */
    public function group()
    {
        return $this->belongsTo("ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\ProductGroup", 'gid');
    }

    public function productGroup()
    {
        return $this->belongsTo("ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\ProductGroup", 'gid');
    }

    public function upgrades()
    {
        return $this->hasMany("ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\ProductUpgrade", 'product_id');
    }

    public function upgradeProducts()
    {
        return $this->belongsToMany("ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Product", "tblproduct_upgrade_products", "product_id", "upgrade_product_id");
    }

    public function services()
    {
        return $this->hasMany("ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service", "packageid");
    }

    public function customFields()
    {
        return $this->hasMany("ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\CustomField", "relid")
            ->where("type", "=", "product")
            ->orderBy("sortorder");
    }
}
