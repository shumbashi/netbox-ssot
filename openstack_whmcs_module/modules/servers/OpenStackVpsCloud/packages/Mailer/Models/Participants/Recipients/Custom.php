<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipients;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Message;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipient;

class Custom extends Recipient
{
    public function __construct(string $email, string $name = "")
    {
        parent::__construct($email, $name, self::TYPE_CUSTOM);
    }

    public function addEmailLog(Message $message)
    {
        // TODO or not TODO
    }
}