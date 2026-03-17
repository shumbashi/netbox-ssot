<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Templates;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Ticket;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Source\AbstractEmailTemplate;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipient;

class Support extends AbstractEmailTemplate
{
    protected const REPLY_EMAILS = ["Support Ticket Opened by Admin", "Support Ticket Reply"];
    public function getRecipient(): Recipient
    {
        $ticket = Ticket::findOrFail($this->relId);
        return Recipient::createFromClient($ticket->userid);
    }

    public function canBeLogged(): bool
    {
        $isTicketReplyEmail = in_array($this->getTemplateName(), self::REPLY_EMAILS);

        $ticketEmailLoggingDisabled = \WHMCS\Config\Setting::getValue("DisableSupportTicketReplyEmailsLogging");

        return !($isTicketReplyEmail && $ticketEmailLoggingDisabled);
    }
}