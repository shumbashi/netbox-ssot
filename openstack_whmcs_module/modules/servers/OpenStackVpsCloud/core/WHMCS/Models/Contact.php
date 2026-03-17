<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models;

class Contact extends \WHMCS\User\Client\Contact
{
    public function client()
    {
        return $this->belongsTo("ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Client", "userid");
    }

    public function orders()
    {
        return $this->hasMany("ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Order", "id", "orderid");
    }
}
