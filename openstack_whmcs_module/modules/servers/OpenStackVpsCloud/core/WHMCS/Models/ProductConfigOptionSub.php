<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models;

class ProductConfigOptionSub extends \WHMCS\Product\ConfigOptionSelection
{
    public function configOption()
    {
        return $this->belongsTo("ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\ProductConfigOption", 'configid');
    }
}
