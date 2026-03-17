<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\RelatedItem\ItemTypes;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\RelatedItem\ItemTypeWithModel;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\RelatedItem\RelatedItem;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\URL;

class Invoice extends ItemTypeWithModel
{
    use TranslatorTrait;

    protected static string $modelClass = \ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Invoice::class;

    public function generateUrl(): string
    {
        $this->getModel();

        return URL\Admin::invoices($this->id);
    }

    public function generateName(): string
    {
        $model = $this->getModel();

        return html_entity_decode('#' . (!empty($model->invoicenum) ? $model->invoicenum : $model->id) . " " . $this->translate(RelatedItem::TYPE_INVOICE));
    }
}