<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Models\Whmcs;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Server;

class Servers extends Server
{
    public static function scopeGetByProductId($query, int $productId)
    {
        return $query->select('tblservers.*')
            ->join('tblservergroupsrel', 'tblservers.id', '=', 'tblservergroupsrel.serverid')
            ->join('tblproducts', 'tblservergroupsrel.groupid', '=', 'tblproducts.servergroup')
            ->where('tblproducts.id', '=', $productId)
            ->first();
    }
}