<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Validators;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator;
use Illuminate\Contracts\Validation\Rule;

class PortRangeOrder implements Rule
{
    protected $min;

    public function __construct($min)
    {
        $this->min = $min;
    }

    public function passes($attribute, $value)
    {
        return $value <= $this->min;
    }

    public function message()
    {
        return Translator::get('validation.firewall_port_range_order');
    }
}