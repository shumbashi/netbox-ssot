<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Models\Whmcs;

use WHMCS\Database\Capsule;

class ProductConfigGroups
{
    public function getConfigurableOptionID(string $optionName, int $productID)
    {
        return Capsule::table('tblproductconfigoptions')
            ->leftJoin('tblproductconfiglinks', 'tblproductconfigoptions.gid', '=', 'tblproductconfiglinks.gid')
            ->join('tblproductconfiggroups', 'tblproductconfigoptions.gid', '=', 'tblproductconfiggroups.id')
            ->select('tblproductconfigoptions.id', 'tblproductconfigoptions.optionname', 'tblproductconfigoptions.gid')
            ->where('tblproductconfigoptions.optionname', 'like', $optionName.'%')
            ->where('tblproductconfiglinks.pid', $productID)->first()->id;
    }
}