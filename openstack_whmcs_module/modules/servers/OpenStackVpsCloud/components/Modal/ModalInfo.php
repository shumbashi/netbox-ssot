<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Modal;

use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonCancel;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonClose;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalClose;

class ModalInfo extends ModalBase
{
    protected $actionModal = false;

 

    protected function initActionButtons()
    {
        $this->addActionButton(
            (new ButtonClose())
                ->setTitle($this->translate('close'))
                ->onClick(new ModalClose($this))
        );
    }
}
