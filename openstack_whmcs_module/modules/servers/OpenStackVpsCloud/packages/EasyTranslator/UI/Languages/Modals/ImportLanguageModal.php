<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalEdit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Forms\ImportLanguageForm;

class ImportLanguageModal extends ModalEdit implements AjaxComponentInterface, AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->addElement(new ImportLanguageForm());
    }
}