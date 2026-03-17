<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Modal;

use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonCancel;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonSuccess;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalClose;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalFormSubmit;

class ModalEdit extends ModalBase
{
    protected $actionModal = false;

 

    protected function initActionButtons()
    {
        $this->addActionButton(
            (new ButtonSuccess())
                ->setTitle($this->translate('submit'))
                ->onClick(new ModalFormSubmit($this))
        );

        $this->addActionButton(
            (new ButtonCancel())
                ->setTitle($this->translate('cancel'))
                ->onClick(new ModalClose($this))
        );
    }
}
