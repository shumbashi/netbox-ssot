<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Providers\Source;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\MailBoxConfiguration\MailBoxConfiguration;

interface MailBoxInterface
{
    public function getModuleName():string;

    /**
     * Params for implementation:
     *
     * 'encoding'
     * 'service_provider'
     * 'host'
     * 'port'
     * 'auth_type'
     * 'username'
     * 'password'
     * 'oauth2_callback_url'
     * 'oauth2_client_id'
     * 'oauth2_client_secret'
     * 'oauth2_refresh_token'
     * 'secure'
     * 'debug'
     */
    public function getConfiguration():MailBoxConfiguration;
}