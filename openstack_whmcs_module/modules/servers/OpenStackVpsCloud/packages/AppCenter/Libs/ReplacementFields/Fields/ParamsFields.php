<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;

class ParamsFields extends BaseFields
{
    const NAME = 'params';

    public function loadColumns(): self
    {
        $this->columns = [
            ['name' => 'serviceid', 'access' => self::ACCESS_OBJECT],
            ['name' => 'userid', 'access' => self::ACCESS_OBJECT],
            ['name' => 'pid', 'access' => self::ACCESS_OBJECT],
            ['name' => 'serverid', 'access' => self::ACCESS_OBJECT],
            ['name' => 'domain', 'access' => self::ACCESS_OBJECT],
            ['name' => 'username', 'access' => self::ACCESS_OBJECT],
            ['name' => 'password', 'access' => self::ACCESS_OBJECT],
            ['name' => 'producttype', 'access' => self::ACCESS_OBJECT],
            ['name' => 'moduletype', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption1', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption2', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption3', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption4', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption5', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption6', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption7', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption8', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption9', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption10', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption11', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption12', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption13', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption14', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption15', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption16', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption17', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption18', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption19', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption20', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption21', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption22', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption23', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoption24', 'access' => self::ACCESS_OBJECT],
            ['name' => 'clientsdetails', 'access' => self::ACCESS_OBJECT],
            ['name' => 'customfields', 'access' => self::ACCESS_OBJECT],
            ['name' => 'configoptions', 'access' => self::ACCESS_OBJECT],
            ['name' => 'server', 'access' => self::ACCESS_OBJECT],
            ['name' => 'serverip', 'access' => self::ACCESS_OBJECT],
            ['name' => 'serverhostname', 'access' => self::ACCESS_OBJECT],
            ['name' => 'serverusername', 'access' => self::ACCESS_OBJECT],
            ['name' => 'serverpassword', 'access' => self::ACCESS_OBJECT],
            ['name' => 'serveraccesshash', 'access' => self::ACCESS_OBJECT],
            ['name' => 'serversecure', 'access' => self::ACCESS_OBJECT],
            ['name' => 'serverport', 'access' => self::ACCESS_OBJECT],
        ];

        return $this;
    }

    public function loadValues(): BaseFields
    {
        foreach (array_column($this->columns, 'name') as $column) {
            $this->instance[$column] = Params::get($column, '');
        }

        return $this;
    }
}