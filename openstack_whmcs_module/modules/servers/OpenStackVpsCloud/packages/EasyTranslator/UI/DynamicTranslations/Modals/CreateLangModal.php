<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\DynamicTranslations\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalEdit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\DynamicTranslations\Forms\CreateLangForm;

class CreateLangModal extends ModalEdit implements AjaxComponentInterface, AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->addElement(new CreateLangForm());
    }
}