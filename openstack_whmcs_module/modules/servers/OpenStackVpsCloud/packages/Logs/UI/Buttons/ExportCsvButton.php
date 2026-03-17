<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\DropdownMenuItem\DropdownMenuItem;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Modals\ExportCsvModal;

class ExportCsvButton extends DropdownMenuItem
{
    public function loadHtml(): void
    {
        $this->setIcon('upload');
        $this->onClick(Action::modalLoad(new ExportCsvModal()));
    }
}