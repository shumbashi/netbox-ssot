<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Source;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\EmailTemplate;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Whmcs\Helpers\WhmcsMailConfiguration;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Attachments\FileAttachment;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Attachments\TextAttachment;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Message;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipient;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Sender;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Senders\Custom as CustomSender;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipients\Custom as CustomRecipient;

abstract class AbstractEmailTemplate
{
    protected string $name;
    protected int $relId;
    protected ?\WHMCS\Mail\Emailer $whmcsMailerReflection;
    protected EmailTemplate $model;

    abstract public function getRecipient():Recipient;

    public function __construct(int $relId, EmailTemplate $model)
    {
        $this->relId = $relId;
        $this->model = $model;
    }

    public function getType():string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    public function buildMessage(): Message
    {
        $message = new Message($this->getType());

        $message->setSender($this->getSender());
        $message->addRecipient($this->getRecipient());
        $message->setCcRecipients($this->getCcRecipients());
        $message->setBccRecipients($this->getBccRecipients());
        $message->setSubject($this->model->subject);
        $message->setBody($this->model->message);
        $message->setAttachments($this->getAttachments());
        $message->setBodyAsPlainText((bool)$this->model->plaintext);

        $message->setEmailTemplate($this);

        return $message;
    }

    public function getRelatedParams(): array
    {
        $this->loadWhmcsMailerReflection();
        return $this->whmcsMailerReflection->getMergeData();
    }

    public function getAttachments(): array
    {
        $attachments = [];

        $this->loadWhmcsMailerReflection();
        $previewAttachments = $this->whmcsMailerReflection->getMessage()->getAttachments() ?: [];

        foreach ($previewAttachments as $attachment)
        {
            $attachments[] = array_key_exists("data", $attachment) ?
                new TextAttachment($attachment["filename"], $attachment["data"]) :
                new FileAttachment($attachment["filename"], $attachment["filepath"]);
        }

        return $attachments;
    }

    public function getCcRecipients():array
    {
        $ccRecipients = [];

        foreach (is_array($this->model->copyTo) ? $this->model->copyTo : [] as $copyToEmail)
        {
            $ccRecipients[] = new CustomRecipient($copyToEmail);
        }

        return $ccRecipients;
    }

    public function getBccRecipients():array
    {
        $bccRecipients = [];

        foreach (is_array($this->model->blindCopyTo) ? $this->model->blindCopyTo : [] as $blindCopyToEmail)
        {
            $bccRecipients[] = new CustomRecipient($blindCopyToEmail);
        }

        return $bccRecipients;
    }

    public function getTemplateName()
    {
        return $this->model->name;
    }
    
    public function getWhmcsMailerReflection()
    {
        $this->loadWhmcsMailerReflection();

        return $this->whmcsMailerReflection;
    }

    public function canBeLogged(): bool
    {
        return true;
    }

    protected function loadWhmcsMailerReflection()
    {
        if (!isset($this->whmcsMailerReflection))
        {
            //$mailer = \WHMCS\Mail\Emailer::factory((new \WHMCS\Mail\Message())->setType($this->getType()), $this->relId);
            $mailer = \WHMCS\Mail\Emailer::factoryByTemplate($this->model, $this->relId);
            $mailer->preview();
            $this->whmcsMailerReflection = $mailer;
        }
    }

    protected function getSender():Sender
    {
        return !empty($this->model->fromemail) ?
            new CustomSender($this->model->fromemail, $this->model->fromname) :
            WhmcsMailConfiguration::getSystemSender();
    }
}