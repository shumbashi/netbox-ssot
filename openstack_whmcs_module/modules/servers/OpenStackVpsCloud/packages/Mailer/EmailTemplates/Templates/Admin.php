<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Templates;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Source\AbstractEmailTemplate;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipient;

class Admin extends AbstractEmailTemplate
{
    public function getRecipient(): Recipient
    {
        return Recipient::createFromAdmin($this->relId);
    }
}