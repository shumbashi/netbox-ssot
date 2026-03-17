<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models;

class Email extends \WHMCS\Mail\Log
{
    public function client()
    {
        return $this->belongsTo("ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Client", 'userid');
    }
}
