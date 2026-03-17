<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Dropdowns;

use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Support\AppStatusTranslator;

class StatusDropdown extends Dropdown
{
    public function __construct()
    {
        parent::__construct();
    }

    public function loadHtml(): void
    {
        $this->setOptions((new AppStatusTranslator())
            ->getAvailableTranslated())
            ->setDefaultValueAsFirstOption()
            ->required();
    }
}