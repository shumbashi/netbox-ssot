<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Templates;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Source\AbstractEmailTemplate;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipient;

class User extends AbstractEmailTemplate
{
    protected const TEMPLATES_NOT_TO_LOG = ["Password Reset Validation"];

    public function getRecipient(): Recipient
    {
        return Recipient::createFromClient(0, $this->relId);
    }

    public function canBeLogged(): bool
    {
        return !in_array($this->getTemplateName(), self::TEMPLATES_NOT_TO_LOG);
    }
}