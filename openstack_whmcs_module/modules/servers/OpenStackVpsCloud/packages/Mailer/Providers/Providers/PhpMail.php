<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Providers\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Config\Config as WhmcsConfig;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Providers\Source\PhpMailerProvider;

class PhpMail extends PhpMailerProvider
{
    public function getMailerInstance(): \WHMCS\Mail\PhpMailer
    {
        $whmcsConfig = new WhmcsConfig();

        $phpMailer = new \WHMCS\Mail\PhpMailer(true);
        $phpMailer->setEncoding((int)$this->configuration->encoding);
        $phpMailer->isMail();

        $phpMailer->XMailer = $whmcsConfig->get("CompanyName");
        $phpMailer->CharSet = $whmcsConfig->get("Charset");

        return $phpMailer;
    }
}