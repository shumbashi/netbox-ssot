<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Repositories;

use WHMCS\Database\Capsule as DB;

class ServerRepository
{
    public static function findByProductId(int $productId)
    {
        return DB::table('tblservers')
            ->select('tblservers.*')
            ->join('tblservergroupsrel', 'tblservers.id', '=', 'tblservergroupsrel.serverid')
            ->join('tblproducts', 'tblservergroupsrel.groupid', '=', 'tblproducts.servergroup')
            ->where('tblproducts.id', '=', $productId)
            ->where('tblservers.disabled', '=', '0')
            ->first();
    }

    public static function findByGroupId(int $serverGroupId)
    {
        return DB::table('tblservers')
            ->select('tblservers.*')
            ->join('tblservergroupsrel', 'tblservers.id', '=', 'tblservergroupsrel.serverid')
            ->where('tblservergroupsrel.groupid', '=', $serverGroupId)
            ->where('tblservers.disabled', '=', '0')
            ->first();
    }

}