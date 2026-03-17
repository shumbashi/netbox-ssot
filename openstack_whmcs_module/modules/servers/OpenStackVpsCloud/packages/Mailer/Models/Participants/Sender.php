<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Senders\Admin;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Senders\Client;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Senders\User;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Admins as AdminModel;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Client as ClientModel;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\User as UserModel;

abstract class Sender extends Participant
{
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