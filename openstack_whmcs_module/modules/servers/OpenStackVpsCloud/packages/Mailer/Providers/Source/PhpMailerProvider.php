<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Providers\Source;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Attachments\TextAttachment;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\MailBoxConfiguration\MailBoxConfiguration;
use \ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Sender;

abstract class PhpMailerProvider extends AbstractProvider
{
    protected \WHMCS\Mail\PhpMailer $mailer;

    abstract function getMailerInstance():\WHMCS\Mail\PhpMailer ;

    public function __construct(MailBoxConfiguration $configuration)
    {
        parent::__construct($configuration);

        $this->mailer = $this->getMailerInstance();
    }

    public function send()
    {
        $this->mailer->send();
    }

    public function setRecipients(array $recipients):self
    {
        foreach ($recipients as $recipient)
        {
            $this->mailer->addAddress($recipient->getEmail(), $recipient->getName());
        }

        return $this;
    }

    public function setCcRecipients(array $recipients):self
    {
        foreach ($recipients as $recipient)
        {
            $this->mailer->addCC($recipient->getEmail(), $recipient->getName());
        }

        return $this;
    }

    public function setBccRecipients(array $recipients):self
    {
        foreach ($recipients as $recipient)
        {
            $this->mailer->addBCC($recipient->getEmail(), $recipient->getName());
        }

        return $this;
    }

    public function setSender(Sender $sender):self
    {
        $this->mailer->setSenderNameAndEmail($sender->getName(), $sender->getEmail());

        return $this;
    }

    public function setSubject(string $subject):self
    {
        $this->mailer->Subject = $subject;

        return $this;
    }

    public function setBody(string $body, $plainText = false):self
    {
        if ($plainText)
        {
            $body = str_ireplace(["<br />","<br>","<br/>"], "\n", $body);
            $this->mailer->Body = str_ireplace("\t", "", $body);
        }
        else
        {
            $this->mailer->MsgHTML(html_entity_decode($body));
        }
        return $this;
    }

    public function setAttachments(array $attachments):self
    {
        foreach ($attachments as $attachment)
        {
            is_a($attachment, TextAttachment::class) ?
                $this->mailer->AddStringAttachment($attachment->getContent(), $attachment->getName()) :
                $this->mailer->addAttachment($attachment->getContent(), $attachment->getName());
        }

        return $this;
    }
}