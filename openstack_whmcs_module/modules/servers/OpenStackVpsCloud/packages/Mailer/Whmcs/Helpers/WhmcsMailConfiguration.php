<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Whmcs\Helpers;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Config\Config as WhmcsConfig;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Sender;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Senders\Custom as CustomSender;

class WhmcsMailConfiguration
{
    public static function getSystemSender(): Sender
    {
        return new CustomSender((new WhmcsConfig())->get("SystemEmailsFromEmail"), (new WhmcsConfig())->get("SystemEmailsFromName"));
    }

    public static function getMailConfig(): array
    {
        return json_decode(\WHMCS\Input\Sanitize::decode(decrypt((new WhmcsConfig())->get("MailConfig"))), true);
    }
}