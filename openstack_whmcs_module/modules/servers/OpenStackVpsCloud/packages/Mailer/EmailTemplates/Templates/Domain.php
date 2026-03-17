<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Templates;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Domain as DomainModel;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Source\AbstractEmailTemplate;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipient;

class Domain extends AbstractEmailTemplate
{
    public function getRecipient(): Recipient
    {
        $domain = DomainModel::findOrFail($this->relId);
        return Recipient::createFromClient($domain->userid);
    }
}