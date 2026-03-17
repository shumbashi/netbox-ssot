<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Senders;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Client as ClientModel;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\SenderWithModel;

class Client extends SenderWithModel
{
    public function __construct(ClientModel $client)
    {
        parent::__construct($client, $client->email, $client->firstname . " " . $client->lastname, self::TYPE_CLIENT, $client->id);
    }
}