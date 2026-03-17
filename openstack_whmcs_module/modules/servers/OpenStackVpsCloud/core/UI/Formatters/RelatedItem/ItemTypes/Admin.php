<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\RelatedItem\ItemTypes;

use ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\RelatedItem\ItemTypeWithModel;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Admins;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\URL;

class Admin extends ItemTypeWithModel
{
    protected static string $modelClass = Admins::class;

    public function generateUrl(): string
    {
        return URL\Admin::adminSummary($this->id);
    }

    public function generateName(): string
    {
        $model = $this->getModel();

        return html_entity_decode('#' . $model->id . " " . $model->username);
    }
}