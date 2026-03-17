<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipients;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Message;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\RecipientWithModel;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Client as ClientModel;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Repositories\EmailLogs;

class Client extends RecipientWithModel
{
    public function __construct(ClientModel $client)
    {
        $fullName = trim($client->firstName . " " . $client->lastName);

        if (!empty($client->companyName))
        {
            $fullName .= " (" . $client->companyName . ")";
        }

        parent::__construct($client, $client->email, $fullName, self::TYPE_CLIENT, $client->id);
    }

    public function addEmailLog(Message $message)
    {
        if (!$message->cenBeLogged())
        {
            echo '<pre>';
            print_r("message can be logged");
            exit();

            return;
        }

        EmailLogs::addLogByClientIdAndMessage($this->model->id, $message);
    }
}