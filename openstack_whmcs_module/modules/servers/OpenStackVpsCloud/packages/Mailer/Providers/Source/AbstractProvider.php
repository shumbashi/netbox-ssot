<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Providers\Source;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\MailBoxConfiguration\MailBoxConfiguration;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Sender;

abstract class AbstractProvider
{
    protected MailBoxConfiguration $configuration;

    abstract function send();
    abstract function setRecipients(array $recipients):self;
    abstract function setCcRecipients(array $recipients):self;
    abstract function setBccRecipients(array $recipients):self;
    abstract function setSender(Sender $sender):self;
    abstract function setSubject(string $subject):self;
    abstract function setBody(string $body, $plainText = false):self;
    abstract function setAttachments(array $attachments):self;

    public function __construct(MailBoxConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

}