<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Forms\DeleteLanguageForm;

class DeleteLanguageModal extends ModalDanger implements AjaxComponentInterface, AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->addElement(new DeleteLanguageForm());
        $this->setTitle($this->translate('title'));
        $this->setContent($this->translate('description'));
    }
}