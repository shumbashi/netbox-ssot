<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Templates;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Source\AbstractEmailTemplate;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipient;

class Notification extends AbstractEmailTemplate
{
    public function getRecipient(): Recipient
    {
        //TODO
        return Recipient::createFromClient(0);
    }
}