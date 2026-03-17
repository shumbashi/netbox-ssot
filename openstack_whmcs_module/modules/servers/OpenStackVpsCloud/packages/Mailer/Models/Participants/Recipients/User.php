<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipients;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Message;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\RecipientWithModel;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\User as UserModel;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Repositories\EmailLogs;

class User extends RecipientWithModel
{
    public function __construct(UserModel $user)
    {
        parent::__construct($user, $user->email, $user->fullName, self::TYPE_USER, $user->id);
    }

    public function addEmailLog(Message $message)
    {
        if (!$message->cenBeLogged())
        {
            return;
        }

        foreach ($this->model->ownedClients() as $client)
        {
            EmailLogs::addLogByClientIdAndMessage($client->id, $message);
        }
    }
}