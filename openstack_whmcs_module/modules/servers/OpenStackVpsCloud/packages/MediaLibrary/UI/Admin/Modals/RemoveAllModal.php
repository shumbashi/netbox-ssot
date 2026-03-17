<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\MediaLibrary\Elements\RemoveModal;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Forms\RemoveAllForm;

class RemoveAllModal extends RemoveModal implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->addElement(new RemoveAllForm());
        $this->setTitle($this->translate('title'));
        $this->setContent($this->translate('description'));
    }
}