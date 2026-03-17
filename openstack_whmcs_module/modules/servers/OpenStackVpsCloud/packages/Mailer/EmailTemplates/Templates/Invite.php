<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Templates;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Source\AbstractEmailTemplate;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipient;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Invite as InviteModel;

class Invite extends AbstractEmailTemplate
{
    public function getRecipient(): Recipient
    {
        $invite = InviteModel::findOrFail($this->relId);
        return Recipient::createFromClient($invite->clientId);
    }
}