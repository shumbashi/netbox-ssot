<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Whmcs\MailBox;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\MailBoxConfiguration\MailBoxConfiguration;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Providers\Source\MailBoxInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Whmcs\Helpers\WhmcsMailConfiguration;

class WhmcsMailBox implements MailBoxInterface
{
    protected array $whmcsMailConfig = [];

    public function __construct()
    {
        $this->whmcsMailConfig = WhmcsMailConfiguration::getMailConfig();
    }

    public function getModuleName(): string
    {
        return $this->whmcsMailConfig['module'];
    }

    public function getConfiguration(): MailBoxConfiguration
    {
        $mailConfigModel = new MailBoxConfiguration();

        foreach ($this->whmcsMailConfig['configuration'] as $key => $value)
        {
            $mailConfigModel->$key = $value;
        }

        return $mailConfigModel;
    }
}