<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Senders;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Admins as AdminModel;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\SenderWithModel;

class Admin extends SenderWithModel
{
    public function __construct(AdminModel $admin)
    {
        parent::__construct($admin, $admin->email, $admin->firstName . " " . $admin->lastName, self::TYPE_ADMIN, $admin->id);
    }
}