<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Message;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipients\Admin;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipients\Client;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipients\User;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Admins as AdminModel;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Client as ClientModel;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\User as UserModel;

abstract class Recipient extends Participant
{
    abstract public function addEmailLog(Message $message);

    public static function createFromClient($modelOrId, $user = null):self
    {
        if ($user)
        {
            return self::createFromUser($user);
        }

        $client = is_a($modelOrId, ClientModel::class) ? $modelOrId : ClientModel::findOrFail($modelOrId);

        return new Client($client);
    }

    public static function createFromAdmin($modelOrId):self
    {
        $admin = is_a($modelOrId, AdminModel::class) ? $modelOrId : AdminModel::findOrFail($modelOrId);

        return new Admin($admin);
    }

    protected static function createFromUser($modelOrId):self
    {
        $user = is_a($modelOrId, UserModel::class) ? $modelOrId : UserModel::findOrFail($modelOrId);

        return new User($user);
    }
}