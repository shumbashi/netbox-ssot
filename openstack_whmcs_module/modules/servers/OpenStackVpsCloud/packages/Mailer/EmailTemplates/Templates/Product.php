<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Templates;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Hosting;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Source\AbstractEmailTemplate;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipient;

class Product extends AbstractEmailTemplate
{
    public function getRecipient(): Recipient
    {
        //TODO refaktor po issue #799
        $service = Hosting::findOrFail($this->relId);
        return Recipient::createFromClient($service->userid);
    }
}