<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Source\AbstractEmailTemplate;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\TemplatesFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Enums\MessageTypes;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Attachments\AbstractAttachment;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Sender;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipient;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Smarty;

class Message
{
    protected string $type;
    protected string $subject = "";
    protected string $body = "";
    protected array $recipients = [];
    protected array $ccRecipients = [];
    protected array $bccRecipients = [];
    protected array $attachments = [];
    protected Sender $sender;
    protected AbstractEmailTemplate $emailTemplate;
    protected ?int $relId = null;
    protected bool $isPlainText = false;

    public function __construct(string $type = MessageTypes::TYPE_GENERAL)
    {
        $this->type = $type;
    }

    public function addRecipient(Recipient $recipient):self
    {
        $this->recipients[] = $recipient;

        return $this;
    }

    public function setRecipients(array $recipients):self
    {
        foreach ($recipients as $recipient)
        {
            $this->addRecipient($recipient);
        }
        return $this;
    }

    public function getRecipients():array
    {
        return $this->recipients;
    }

    public function addCcRecipient(Recipient $recipient):self
    {
        $this->ccRecipients[] = $recipient;

        return $this;
    }

    public function setCcRecipients(array $recipients):self
    {
        foreach ($recipients as $recipient)
        {
            $this->addCcRecipient($recipient);
        }

        return $this;
    }

    public function getCcRecipients():array
    {
        return $this->ccRecipients;
    }

    public function addBccRecipient(Recipient $recipient):self
    {
        $this->bccRecipients[] = $recipient;

        return $this;
    }

    public function setBccRecipients(array $recipients):self
    {
        foreach ($recipients as $recipient)
        {
            $this->addBccRecipient($recipient);
        }

        return $this;
    }

    public function getBccRecipients():array
    {
        return $this->bccRecipients;
    }

    public function getAllRecipients():array
    {
        return array_merge($this->recipients, $this->ccRecipients, $this->bccRecipients);
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getBody(array $extraParams = []): string
    {
        return Smarty::fetch($this->body, array_merge($this->getRelatedParams(), $extraParams));
    }

    public function getRawBody(): string
    {
        return $this->body;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getSubject(array $extraParams = []): string
    {
        return Smarty::fetch($this->subject, array_merge($this->getRelatedParams(), $extraParams));
    }

    public function hasSender(): bool
    {
        return isset($this->sender);
    }

    public function setSender(Sender $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getSender(): Sender
    {
        return $this->sender;
    }

    public function setEmailTemplate(AbstractEmailTemplate $emailTemplate): self
    {
        $this->emailTemplate = $emailTemplate;

        return $this;
    }

    public function getEmailTemplate():?AbstractEmailTemplate
    {
        return $this->emailTemplate ?? null;
    }

    public function hasTemplate():bool
    {
        return isset($this->emailTemplate);
    }

    public function setRelId(int $relId): self
    {
        $this->relId = $relId;

        return $this;
    }

    public function addAttachment(AbstractAttachment $attachment):self
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    public function setAttachments(array $attachments):self
    {
        foreach ($attachments as $attachment)
        {
            $this->addAttachment($attachment);
        }

        return $this;
    }

    public function getAttachments():array
    {
        return $this->attachments;
    }

    public function setBodyAsPlainText(bool $isPlainText)
    {
        $this->isPlainText = $isPlainText;

        return $this;
    }

    public function isPlainText():bool
    {
        return $this->isPlainText;
    }

    public function cenBeLogged():bool
    {
        return !$this->hasTemplate() || $this->emailTemplate->canBeLogged();
    }

    protected function getRelatedParams(): array
    {
        if (isset($this->emailTemplate))
        {
            return $this->emailTemplate->getRelatedParams();
        }

        if ($this->relId)
        {
            return TemplatesFactory::byName($this->type, $this->relId)->getRelatedParams();
        }

        return [];
    }
}