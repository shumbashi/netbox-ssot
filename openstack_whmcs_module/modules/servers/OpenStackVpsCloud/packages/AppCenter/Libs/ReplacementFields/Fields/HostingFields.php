<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Hosting;

class HostingFields extends BaseFields
{
    const LEGACY_NAME = 'hosting';
    const NAME = 'service';
    protected $model = Hosting::class;

    public function loadValues(): self
    {
        parent::loadValues();

        if (!empty($this->instance['password'])) {
            $this->instance['password'] = html_entity_decode(\decrypt($this->instance['password']));
        }

        return $this;
    }
}