<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Providers;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Providers\Source\AbstractProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Providers\Source\MailBoxInterface;

class ProvidersManager
{
    public static function getMailProvider(MailBoxInterface $mailBox = null):AbstractProvider
    {
        return ProvidersFactory::byName($mailBox->getModuleName(), $mailBox->getConfiguration());
    }
}