<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Form\Data;

use function ModulesGarden\OpenStackVpsCloud\Core\validator;

class Validate
{
    public function run(array $elements)
    {
        $validatableElementsBag = new ValidatableElementsBag($elements);

        $input = array_merge(
            \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request::get('formData', []),
            \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request::files()->get('formData', [])
        );

        validator()->validate(
            $input ?? [],
            $validatableElementsBag->getValidators(),
            $validatableElementsBag->getCustomAttributes(),
            $validatableElementsBag->getCustomValues()
        );
    }
}
