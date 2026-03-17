<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Senders;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\User as UserModel;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\SenderWithModel;

class User extends SenderWithModel
{
    public function __construct(UserModel $user)
    {
        parent::__construct($user, $user->email, $user->firstname . " " . $user->lastname, self::TYPE_USER, $user->id);
    }
}