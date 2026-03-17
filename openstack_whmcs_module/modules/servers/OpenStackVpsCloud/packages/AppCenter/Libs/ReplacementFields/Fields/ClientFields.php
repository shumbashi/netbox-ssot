<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields;


use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Client;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\CustomField;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\CustomFieldValue;

class ClientFields extends BaseFields
{
    const NAME = 'client';

    protected $model = Client::class;

    public function loadColumns(): self
    {
        $customFields = CustomField::where('type', 'client')->get();

        foreach ($customFields as $customField) {
            $field = explode('|', $customField->fieldname);
            $this->columns[] = ['name' => $field[0], 'access' => $field[1] ? self::ACCESS_OBJECT : self::ACCESS_ARRAY];
        }

        return parent::loadColumns();
    }

    public function loadValues(): self
    {
        parent::loadValues();

        $customFields = CustomField::where('type', 'client')->get();

        foreach ($customFields as $customField) {
            $field = explode('|', $customField->fieldname);

            $exists = CustomFieldValue::where('fieldid', $customField->id)->where('relid', $this->id)->exists();
            if (!$exists) {
                $this->instance[$field[0]] = '';
                continue;
            }

            $this->instance[$field[0]] = $customField->getValueByRelid($this->id);
        }

        return $this;
    }
}