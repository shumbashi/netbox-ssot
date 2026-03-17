<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Helpers;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Attachments\AbstractAttachment;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Message;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipient;

class MessageFormatter
{
    protected Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function getFormattedRecipients():array
    {
        return $this->formatRecipients($this->message->getRecipients());
    }

    public function getFormattedCcRecipients():array
    {
        return $this->formatRecipients($this->message->getCcRecipients());
    }

    public function getFormattedBccRecipients():array
    {
        return $this->formatRecipients($this->message->getBccRecipients());
    }

    public function getAttachmentNames():array
    {
        return array_map(function(AbstractAttachment $attachment) {
            return $attachment->getName();
        }, $this->message->getAttachments());
    }

    protected function formatRecipients(array $recipients = []):array
    {
        return array_map(function(Recipient $recipient) {
            $email = $recipient->getEmail();
            $name = $recipient->getName();

            return empty($name) ? $email : $name . " <" . $email . ">";
        }, $recipients);
    }
}