<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Modal;

use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonCancel;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalClose;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalFormSubmit;

class ModalDanger extends ModalAction
{
    protected $type = self::TYPE_DANGER;

    protected function initActionButtons()
    {
        $this->addActionButton(
            (new ButtonDanger())
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
