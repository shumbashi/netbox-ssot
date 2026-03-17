<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Validation\Rules;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Validation\ImplicitRuleInterface;

class Sample implements ImplicitRuleInterface
{
    public function passes($attribute, $value)
    {
        return false;
    }

    public function message()
    {
        return '';
    }
}