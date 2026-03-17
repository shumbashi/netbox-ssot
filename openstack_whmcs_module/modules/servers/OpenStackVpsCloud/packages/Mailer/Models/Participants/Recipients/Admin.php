<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipients;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Message;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\RecipientWithModel;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Admins as AdminModel;

class Admin extends RecipientWithModel
{
    public function __construct(AdminModel $admin)
    {
        parent::__construct($admin, $admin->email, $admin->firstName . " " . $admin->lastName, self::TYPE_ADMIN, $admin->id);
    }

    public function addEmailLog(Message $message)
    {
        // TODO or not TODO
    }
}