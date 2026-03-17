<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\Services;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Helpers\ConfigurableOptionHelper;

class ServiceCustomFieldsValues
{
    protected int $serviceId;
    protected $customFieldsValues = null;

    public function __construct(int $serviceId)
    {
        $this->serviceId = $serviceId;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $this->loadCustomFieldsValues();

        $customFieldsValues = [];

        foreach ($this->customFieldsValues as $customFieldValue)
        {
            $customFieldsValues[ConfigurableOptionHelper::parseConfigOptionName($customFieldValue->customField->fieldname)] = $customFieldValue->value;
        }

        return $customFieldsValues;
    }

    public function set(array $customFields)
    {
        if (!function_exists("saveCustomFields")) {
            require ROOTDIR . "/includes/customfieldfunctions.php";
        }

        saveCustomFields($this->serviceId, $customFields, "product", true);
    }

    protected function loadCustomFieldsValues()
    {
        if ($this->customFieldsValues === null)
        {
            $this->customFieldsValues = Service::find($this->serviceId)->customFieldValues;
        }
    }
}