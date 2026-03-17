<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Senders;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Sender;

class Custom extends Sender
{
    public function __construct(string $email, string $name)
    {
        parent::__construct($email, $name, self::TYPE_CUSTOM);
    }
}