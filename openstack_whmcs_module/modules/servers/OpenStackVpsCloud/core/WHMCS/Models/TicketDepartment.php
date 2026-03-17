<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models;

class TicketDepartment extends \WHMCS\Support\Department
{
    public function tickets()
    {
        return $this->hasMany('ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Ticket', "did");
    }
}
