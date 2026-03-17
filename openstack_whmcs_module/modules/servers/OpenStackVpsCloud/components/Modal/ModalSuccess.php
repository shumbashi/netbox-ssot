<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Modal;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalClose;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalFormSubmit;

class ModalSuccess extends ModalAction
{
    protected $type = self::TYPE_SUCCESS;

    protected function initActionButtons()
    {
        $this->addActionButton(
            (new \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonSuccess())
                ->setTitle($this->translate('submit'))
                ->onClick(new ModalFormSubmit($this))
        );

        $this->addActionButton(
            (new \ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonCancel())
                ->setTitle($this->translate('cancel'))
                ->onClick(new ModalClose($this))
        );
    }
}
