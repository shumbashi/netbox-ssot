<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models;

class ProductGroup extends \WHMCS\Product\Group
{
    public function products()
    {
        return $this->hasMany("ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Product", 'gid');
    }
}
