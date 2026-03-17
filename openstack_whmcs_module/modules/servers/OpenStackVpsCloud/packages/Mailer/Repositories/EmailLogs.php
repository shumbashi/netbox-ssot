<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Repositories;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Source\AbstractEmailTemplate;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Helpers\MessageFormatter;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Message;

class EmailLogs
{
    public static function addLogByClientIdAndMessage(int $clientId, Message $message)
    {
        $message->hasTemplate() ?
            self::logFromWhmcsTemplate($clientId, $message->getEmailTemplate()) :
            self::logCustomMessage($clientId, $message);

    }

    public static function logFromWhmcsTemplate(int $clientId, AbstractEmailTemplate $template)
    {
        $whmcsMessageReflection = $template->getWhmcsMailerReflection()->getMessage();
        return $whmcsMessageReflection->saveToEmailLog($clientId);
    }

    public static function logCustomMessage(int $clientId, Message $message)
    {
        $nowTime = (new \DateTime('now'))->format('Y-m-d H:i:s');

        $formatter = new MessageFormatter($message);

        $emailData = [
            "userid"        => $clientId,
            "date"          => $nowTime,
            "to"            => implode(", ", $formatter->getFormattedRecipients()),
            "cc"            => implode(", ", $formatter->getFormattedCcRecipients()),
            "bcc"           => implode(", ", $formatter->getFormattedBccRecipients()),
            "subject"       => $message->getSubject(),
            "message"       => $message->getBody(),
            "pending"       => false,
            "failed"        => false,
            "attachments"   => json_encode($formatter->getAttachmentNames()),
            "updated_at"    => $nowTime
        ];

        return \WHMCS\Database\Capsule::table("tblemails")->insertGetId($emailData);
    }
}