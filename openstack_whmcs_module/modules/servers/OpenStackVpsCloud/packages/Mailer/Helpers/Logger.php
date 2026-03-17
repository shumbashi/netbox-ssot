<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Helpers;

use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Message;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipient;
use function ModulesGarden\OpenStackVpsCloud\Core\translator;

class Logger
{
    use TranslatorTrait;

    public function success(Message $message)
    {
        foreach ($message->getAllRecipients() as $recipient)
        {
            $translatedMessage = $this->getTranslatedSuccessMessage($message, $recipient);

            $params = [];

            $params['name'] = $recipient->getName();
            $params['email'] = $recipient->getEmail();

            if ($clientId = $recipient->getRelId())
            {
                $params['recipientType'] = $recipient->getType();
                $params['recipientId'] = $clientId;
            }

            $this->addModuleLogSuccess($translatedMessage, $params);
            $this->addWhmcsLog($translatedMessage);
            $this->addRecipientEmailLog($recipient, $message);
        }
    }

    public function error(Message $message, string $errorMessage)
    {
        $translatedMessage = $this->getTranslatedErrorMessage($message, $errorMessage);

        $this->addModuleLogError($translatedMessage);
        $this->addWhmcsLog($translatedMessage);
    }

    protected function getTranslatedSuccessMessage(Message $message, Recipient $recipient):string
    {
        $messageContent = translator()->get('packages.mailer.logs.success.emailSendTo');
        $messageContent .= !empty($recipient->getName()) ? $recipient->getName() : $recipient->getEmail();
        $messageContent .= " ({$message->getSubject()})";

        if ($clientId = $recipient->getRelId())
        {
            $messageContent .= " " . ucfirst($recipient->getType()) . " ID: " . $clientId . ".";
        }

        return $messageContent;
    }

    protected function getTranslatedErrorMessage(Message $message, string $errorMessage):string
    {
        $messageContent = translator()->get('packages.mailer.logs.error.emailSendingFailed');
        $messageContent .= $errorMessage;
        $messageContent .= " (" . translator()->get('packages.mailer.logs.subjectMessage') . " " . $message->getSubject(). ")";

        return $messageContent;
    }

    protected function addModuleLogSuccess(string $message, array $params = [])
    {
        if (\ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('mailer.moduleLogging', false))
        {
            \ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Facades\Logger::info($message, $params);
        }
    }

    protected function addModuleLogError(string $message, array $params = [])
    {
        if (\ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('mailer.moduleLogging', false))
        {
            \ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Facades\Logger::error($message, $params);
        }
    }

    protected function addWhmcsLog(string $message)
    {
        if (\ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('mailer.whmcsLogging', false))
        {
            if (!function_exists("logActivity"))
            {
                require ROOTDIR . "/includes/functions.php";
            }

            logActivity($message);
        }
    }

    protected function addRecipientEmailLog(Recipient $recipient, Message $message)
    {
        if (\ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('mailer.clientLogging', false))
        {
            $recipient->addEmailLog($message);
        }
    }

}