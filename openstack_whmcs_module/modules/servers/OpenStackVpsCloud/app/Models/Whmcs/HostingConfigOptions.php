<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Models\Whmcs;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\HostingConfigOption as CoreModel;

class HostingConfigOptions extends CoreModel
{

    public function getConfigurableOptionName(string $optionName, int $hostingId)
    {
        return $this->select('tblproductconfigoptionssub.optionname')
            ->leftJoin('tblproductconfigoptionssub', 'tblhostingconfigoptions.configid', '=', 'tblproductconfigoptionssub.configid')
            ->where('tblhostingconfigoptions.relid', '=', $hostingId)
            ->where('tblproductconfigoptionssub.optionname', 'like', $optionName.'|%')
            ->first()
            ->optionname;
    }
}