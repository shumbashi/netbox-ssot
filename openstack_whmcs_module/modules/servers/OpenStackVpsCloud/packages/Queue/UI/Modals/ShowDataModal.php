<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalBase;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Widgets\TaskDetailsTabs;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class ShowDataModal extends ModalBase implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->addElement(new TaskDetailsTabs());
        $this->setTitle($this->translate('title'));
        $this->setSize(self::SIZE_LARGE);
    }
}
