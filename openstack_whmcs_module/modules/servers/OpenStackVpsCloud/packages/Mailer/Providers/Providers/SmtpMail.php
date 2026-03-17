<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Providers\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Config\Config as WhmcsConfig;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Providers\Source\PhpMailerProvider;

class SmtpMail extends PhpMailerProvider
{
    public function getMailerInstance(): \WHMCS\Mail\PhpMailer
    {
        $whmcsConfig = new WhmcsConfig();

        $phpMailer = new \WHMCS\Mail\PhpMailer(true);
        $phpMailer->setEncoding((int)$this->configuration->encoding);
        $phpMailer->IsSMTP();
        $phpMailer->SMTPAutoTLS = false;
        $phpMailer->Host = $this->configuration->host;
        $phpMailer->Port = $this->configuration->port;
        $phpMailer->Hostname = $phpMailer->serverHostname();

        if ($this->configuration->secure)
        {
            $phpMailer->SMTPSecure = $this->configuration->secure;
        }

        if ($this->configuration->username)
        {
            $phpMailer->SMTPAuth = true;
            if (empty($this->configuration->auth_type) || $this->configuration->auth_type === \WHMCS\Mail\MailAuthHandler::AUTH_TYPE_PLAIN)
            {
                $phpMailer->Username = $this->configuration->username;
                $phpMailer->Password = $this->configuration->password;
            }
            else
            {
                $phpMailer->AuthType = "XOAUTH2";
                $oauthHandler = new \WHMCS\Mail\MailAuthHandler();
                $oauth = new \PHPMailer\PHPMailer\OAuth([
                    "provider" => $oauthHandler->createProvider(
                        $this->configuration->service_provider,
                        $this->configuration->oauth2_client_id,
                        $this->configuration->oauth2_client_secret,
                        \WHMCS\Mail\MailAuthHandler::CONTEXT_SMTP_MAIL
                    ),
                    "userName" => $this->configuration->username,
                    "clientId" => $this->configuration->oauth2_client_id,
                    "clientSecret" => $this->configuration->oauth2_client_secret,
                    "refreshToken" => $this->configuration->oauth2_refresh_token
                ]);
                $phpMailer->setOAuth($oauth);
            }
        }

        $phpMailer->XMailer = $whmcsConfig->get("CompanyName");
        $phpMailer->CharSet = $whmcsConfig->get("Charset");

        return $phpMailer;
    }
}