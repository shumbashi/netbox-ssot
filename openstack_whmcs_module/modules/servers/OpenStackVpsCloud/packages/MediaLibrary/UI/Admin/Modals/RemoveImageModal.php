<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\MediaLibrary\Elements\RemoveModal;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Forms\RemoveImageForm;

class RemoveImageModal extends RemoveModal implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->addElement(new RemoveImageForm());
        $this->setTitle($this->translate('title'));
        $this->setContent($this->translate('description'));
    }
}