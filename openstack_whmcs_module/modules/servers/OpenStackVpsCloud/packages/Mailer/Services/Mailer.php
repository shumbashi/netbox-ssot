<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Services;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\EmailTemplate;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Providers\Source\MailBoxInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\TemplatesFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Helpers\Logger;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Helpers\WhmcsMailConfiguration;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Message;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Providers\ProvidersManager;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Whmcs\MailBox\WhmcsMailBox;

class Mailer
{
    protected MailBoxInterface $mailBox;

    public function __construct(MailBoxInterface $mailBox = null)
    {
        $this->mailBox = $mailBox ?: new WhmcsMailBox();
    }

    public function sendFromTemplate(string $templateName, $relId, array $extraParams = [])
    {
        $template = TemplatesFactory::byName($templateName, $relId);
        $message = $template->buildMessage();

        $this->send($message, $extraParams);
    }

    public function send(Message $message, array $extraParams = [])
    {
        $logger = \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('mailer.logger', new Logger());

        try {
            $mailProvider = ProvidersManager::getMailProvider($this->mailBox);

            $mailProvider->setRecipients($message->getRecipients());
            $mailProvider->setCcRecipients($message->getCcRecipients());
            $mailProvider->setBccRecipients($message->getBccRecipients());
            $mailProvider->setSender($message->hasSender() ? $message->getSender() : WhmcsMailConfiguration::getSystemSender());
            $mailProvider->setSubject($message->getSubject($extraParams));
            $mailProvider->setBody($message->getBody($extraParams), $message->isPlainText());
            $mailProvider->setAttachments($message->getAttachments());

            $mailProvider->send();

            $logger->success($message);

        } catch (\Exception $e) {
            $logger->error($message, $e->getMessage());
        }
    }
}