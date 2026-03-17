<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Templates;

use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Source\AbstractEmailTemplate;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\Recipient;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Invoice as InvoiceModel;

class Invoice extends AbstractEmailTemplate
{
    public function getRecipient(): Recipient
    {
        $invoice = InvoiceModel::findOrFail($this->relId);
        return Recipient::createFromClient($invoice->userid);
    }
}